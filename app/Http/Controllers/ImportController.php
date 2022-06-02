<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class ImportController extends Controller
{

    private function array_to_csv_generator($array, $number)
    {

      echo "<hr/><a style='color:red;'><b>Creating CSV file at [primavera_import/public/]</b></a><br/>";

      if($number>0)
      {
        /*
        //Calculate backup name
        $yesterdayDate = date('d-m-Y',strtotime("-1 days"));
        $yesterdayName = "output_".$yesterdayDate.".csv";

        //Check if backup exists
        if(file_exists($yesterdayName))
        {
          echo "Backup from $yesterdayDate exists! Launching scan to check for product changes.<br />";

          $csv = array_map('str_getcsv', file($yesterdayName));
          foreach($csv as $line)
          {
            print_r($line);
          }
        }*/

        //Generate CSV from array
        $currentDate = date("d-m-Y");
        $name = "output_".$currentDate.".csv";
        file_put_contents($name, $array);
        file_put_contents('output.csv', $array);
        echo "<hr/><a style='color:green;'><b>***Work done!***</b></a><br/>";

      }else {
        echo "<hr/><a style='color:red;'><b>***Alert! CSV couldn't be created. Nothing retrieved from database.***</b></a><br/>";
      }


    }

    public function get()
    {

      //Define Short Description as empty in case nothing is retrieved from database
      $shortDescription = "";
      //Number of parses
      $number = 0;
      //CSV Headers - Column names
      $finalArray[] = "SKU;"."Product Name;"."Published;"."Featured;"."Short Description;"."Allow Customer Reviews;"."Regular Price;"."Sale Price;"."img;"."Category;\n";

      echo "<a style='color:red;'><b>>[Querying database.. please wait..]</b></a><br/>";

      $result = DB::select('SELECT Artigo, Descricao, Familia, SubFamilia, CDU_site, CDU_destaque, CDU_pvp1site, CDU_pvp2site, CDU_subcategoria ,Iva FROM dbo.Artigo WHERE CDU_site = 1');
      foreach ($result as $line)
      {

          $number +=1;
          $referal = $line->Artigo;
          $productName = $line->Descricao;
          $family = $line->Familia;
          $subFamily = $line->SubFamilia;
          $subSubFamily = $line->CDU_subcategoria;

          //Prepares CSV to WP plugin compatibility. Case 0 writes 'no', case 1 writes 'yes'.
          $published = $line->CDU_site;
          if($published == 0 )
          {
            $published = "no";
          }else {
            $published = "yes";
          }

          //Prepares CSV to WP plugin compatibility. Case 0 writes 'no', case 1 writes 'yes'.
          $isFeatured = $line->CDU_destaque;
          if($isFeatured = 0)
          {
            $isFeatured = "no";
          }else {
            $isFeatured = "yes";
          }

          //Prepares CSV to WP plugin compatibility. Sets 'no' as default, write 'yes' to allow by default Customer Reviews.
          $allowCustomerReviews = "no";

          //If regular price is less than 1 the variable will recieve an empty string
          $regularPrice = number_format($line->CDU_pvp1site, 3, '.', '');
          $resultado = DB::select("SELECT top 1 PVP1 FROM dbo.ArtigoMoeda WHERE  Artigo = '" . $referal . "'");
          $iva = $line->Iva;
          $temp =  $iva * 0.01;
          foreach ($resultado as $linha) {
          $regularPrice = number_format( $linha->PVP1, 3, '.', '');
          $temp2 = $regularPrice * $temp;
          $regularPrice = $temp2 + $regularPrice;
          }
      if ($regularPrice < 0.01) {
        $regularPrice = "";
      }

          //If sale price is less than 1 the variable will recieve an empty string
          $salePrice = $line->CDU_pvp2site;
          if($salePrice<0.01)
          {
            $salePrice = "";
          }
          //Get Short Description
          $query2 = "SELECT DescricaoComercial FROM dbo.ArtigoIdioma WHERE Artigo ='".$referal."'";
          $result2 = DB::select($query2);

          foreach ($result2 as $line2)
          {
              $shortDescription = $line2->DescricaoComercial;
          }
		
		  // Remove break line in Description [lgmlleal] &gt;
		  $shortDescription = str_replace(array("\r", "\n"), '', $shortDescription);	
		  $shortDescription .= "";

          //Get Family Name Level 1
          $query3 = "SELECT Descricao FROM dbo.Familias WHERE Familia ='".$family."'";
          $result3 = DB::select($query3);

          foreach ($result3 as $line3)
          {
              $familyNameLevel1 = $line3->Descricao;
          }

          //Get Family Name Level 2
          $query4 = "SELECT Descricao FROM dbo.SubFamilias WHERE Familia ='".$family."' AND SubFamilia ='".$subFamily."'";
          $result4 = DB::select($query4);

          foreach ($result4 as $line4)
          {
              $familyNameLevel2 = $line4->Descricao;
          }
          
          //Get Family Name Level 2
          $query4 = "SELECT * FROM PRI104311MEGA.dbo.Anexos WHERE Chave= '".$referal."';";
          $result4 = DB::select($query4);
          $c =0;
          $img="";
          foreach ($result4 as $line4)
          {
            $slipt=explode(".",$line4->FicheiroOrig);
            $id =$line4->Id;
            $file= "{".$id."}.".end($slipt);
             if ($c == 0) {
            $img = "https://sinc.megafrica.ao/getimages/img/". str_replace("-","_",str_replace(".","",$referal))."/".$file;
            $c++;
          }else{
            $img .= "~https://sinc.megafrica.ao/getimages/img/". str_replace("-","_",str_replace(".","",$referal))."/".$file;
          }
          
          }

          /*Implement Family Name Level 3 for Woocommerce here*/

          echo "<b> > Adding results.. Parse number[".$number."]</b><br/>";

          //Prepare row to insert in $finalArray (Array type)
          if(empty($subSubFamily)){
            $csvString = $referal.";".$productName.";".$published.";".$isFeatured.";".$shortDescription.";".$allowCustomerReviews.";".$regularPrice.";".$salePrice.";".$img.";".$familyNameLevel1." > ".$familyNameLevel2.";\n";
          }else{
            $csvString = $referal.";".$productName.";".$published.";".$isFeatured.";".$shortDescription.";".$allowCustomerReviews.";".$regularPrice.";".$salePrice.";".$img.";".$familyNameLevel1." > ".$familyNameLevel2." > ".$subSubFamily.";\n";
          }
          $finalArray[] = $csvString;
          //Clean Short Description variable for next use. In case nothing is retrieved for the next product nothing is inserted in this space.
          $shortDescription="";
      }
        //Implementation for CSV generator
        $this->array_to_csv_generator($finalArray, $number);
    }
}
