<?php
header('Content-Type: text/html; charset=utf-8');

$hostname_conexao = "localhost";
$database_conexao = "auto_code";
$username_conexao = "root";
$password_conexao = "";

$conexao = mysqli_connect($hostname_conexao, $username_conexao, $password_conexao) or die('Could not connect: ' . mysqli_connect_error());

mysqli_query($conexao, "SET NAMES 'utf8'");
mysqli_query($conexao, 'SET character_set_connection=utf8');
mysqli_query($conexao, 'SET character_set_client=utf8');
mysqli_query($conexao, 'SET character_set_results=utf8');
?>