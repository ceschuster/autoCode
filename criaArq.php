<?php
	
	$conteudo = $_POST["conteudo"];
	$nome = $_POST["nome"];

	$myfile = fopen("arqs/".$nome, "w") or die("Unable to open file!");
	fwrite($myfile, $conteudo);
	fclose($myfile);
	
?>