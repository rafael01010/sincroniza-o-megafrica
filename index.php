<?php
 #header("Location: wwwroot/public/export/index.php" );


$cb_password = "Kdcnl@6@!bT9";


echo '

<div style="width: 100vw; height: 100vh;  background: #FFF;   display: flex;   flex-direction: row;   justify-content: center;    align-items: center">

	<div style="  width: 400px;   height: 400px;    background: #fff;">
    
		<img src="logo.png" border="0"  width="340px" />
		<br><br><br>
		<form method="POST" action="index.php">
			
			<input type="password" name="comando" id="comando" placeholder="Digite o comando" style=" border: none; border-bottom: 2px solid blue; ">
			<input type="submit" style=" width: 50%;border-radius: 1rem;padding: 1.5%;border: none;cursor: pointer;" value="Importar" id="Importar" name="Importar">
			    
	    
		</form>
	</div>
</div>


';


if($_POST) {

	$pass = $_POST['comando'];
	echo $pass . " - ". $cb_password;

	if($pass == $cb_password ) {
		
		header("Location: /public/export/csv" );
	}
	
	echo "<br>POST!";
	

} else 	{

		//echo "<br>Ops! tam nada nao.";
}

?>




