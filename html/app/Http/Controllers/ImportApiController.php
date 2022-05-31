<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;
use DB;

class ImportApiController extends Controller
{

  public function get()
  {
    /*
     Instalar package: composer install automattic/woocommerce
      1. Verifica a existencia de uma categoria com nome X (REGEX)
          //// mesmo para sub categoria
            //// mesmo para subcategoria
         Caso exista obtem a ID
         Caso não exista cria e obtem a ID

    */
    //Define Woocommerce API connection
    $woocommerce = new Client(
	     'http://dev2.megafrica.co.ao',
	      'ck_ea3c27a936ddfe773867f4a5e4585f5b8cd74e5c',
	       'cs_fce5bcee8f83ea9805bbb6734895d685a5a768b8',
	        [
		          'wp_api'  => true,
		          'version' => 'wc/v3',
	        ]
        );

        $prod_data = [
          'name' => 'Premium Quality',
        ];
          print_r($woocommerce->get('products/categories'));


        //$woocommerce->post( 'products', json_encode($prod_data) );
        /*
    //Define Short Description as empty in case nothing is retrieved from database
    $shortDescription = "";
    //Number of parses
    $number = 0;

    echo "<a style='color:red;'><b>>[Querying database.. please wait..]</b></a><br/>";

    //Apagar "top 1000" quando for para descarregar tudo.
    $result = DB::select('SELECT TOP 10 Artigo, Descricao, Familia, SubFamilia, CDU_site, CDU_destaque, CDU_pvp1site, CDU_pvp2site FROM dbo.Artigo WHERE CDU_site = 0');
    foreach ($result as $line)
    {

        $number +=1;
        $referal = $line->Artigo;
        $productName = $line->Descricao;
        $family = $line->Familia;
        $subFamily = $line->SubFamilia;
        $published = $line->CDU_site;
        $isFeatured = $line->CDU_destaque;
        $allowCustomerReviews = 0;

        //If regular price is less than 1 the variable will recieve an empty string
        $regularPrice = $line->CDU_pvp1site;
        if($regularPrice<0.01){
          $regularPrice = "";
        }

        //If sale price is less than 1 the variable will recieve an empty string
        $salePrice = $line->CDU_pvp2site;
        if($salePrice<0.01){
          $salePrice = "";
        }

        //Get Short Description
        $query2 = "SELECT DescricaoComercial FROM dbo.ArtigoIdioma WHERE Artigo ='".$referal."'";
        $result2 = DB::select($query2);

        foreach ($result2 as $line2)
        {
            $shortDescription = $line2->DescricaoComercial;
        }

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
        }*/

        /*Implement Family Name Level 3 for Woocommerce here*/
        /*
        echo "<b> > Adding results.. Parse number[".$number."]</b><br/>";

        $prod_data = [
	         'name'          => 'A great product',
	         'type'          => 'simple',
	         'regular_price' => '15.00',
	         'description'   => 'A very meaningful product description',
	         'categories'    => [
		           [
			              'id' => 1,
		           ],
	         ],
        ];

        $woocommerce->post( 'products', $prod_data );

        //Clean Short Description variable for next use. In case nothing is retrieved for the next product nothing is inserted in this space.
        $shortDescription="";

    }*/

  }
}
