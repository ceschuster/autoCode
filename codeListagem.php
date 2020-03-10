<?php
	$sql = trim($_POST["sql"]);
	$sql2="CREATE TABLE `quartos` (
	`id_quarto` int(11) NOT NULL,
	`id_hotel` int(11) NOT NULL,
	`tipo` varchar(50) NOT NULL,
	`lotacao` int(11) NOT NULL,
	`valor` float(7,2) NOT NULL,
	`post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`qtde` int(11) NOT NULL,
	`obs` text NOT NULL DEFAULT '0',
	`status` char(3) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;
	";
	$linhas = explode("\n",$sql);
	$qtLinhas = sizeof($linhas)-1;
	
	
	$nomeTabela = "";
	preg_match('/`([^"]+)`/', $linhas[0], $valor);
	
	//
	//
	//
	//
	//
	$nomeTabela = $valor[1];
	$camposNome[]="";
	$camposTipo[]="";
	$camposTam[]="";
	//
	//
	//
	//
	//
	for($i=1;$i<$qtLinhas;$i++){
		// nome dos campos
		preg_match('/`([^"]+)`/', trim($linhas[$i]), $valor);
		$camposNome[$i] = $valor[1];
		
		// tipo dos campos
		$temp = explode(" ",trim($linhas[$i])); $temp = trim($temp[1]);
		if(strstr($temp, '(')) {
			$temp2=explode("(",$temp);
			$camposTipo[$i] = $temp2[0];
		} else {$camposTipo[$i]=$temp;}
		
		// tamanho dos campos
		$temp = explode(" ",trim($linhas[$i])); $temp = trim($temp[1]);
		if(strstr($temp, '(')) {
			preg_match('/(?<=\()(.+)(?=\))/is', trim($linhas[$i]), $valor);
			$camposTam[$i] = $valor[0];
		} else {$camposTam[$i]="N/A";}
		
		//echo $camposNome[$i] ." | ". $camposTipo[$i] ." | ". $camposTam[$i]."\r";
	}
	
	//
	//
	//	LISTAGEM DE DADOS EM TABELA
	//
	//
	
$txt='<?php 
if (!isset($_SESSION)) {
	session_start();
} 

require_once("conexao.php");

$_SESSION["token"] = md5(uniqid(rand(), true)); 


?>
';
echo $txt;
?>
<html>
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo $nomeTabela;?></title>
		
		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
		
		<link rel="stylesheet" type="text/css" href="lightpick.css">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		


	</head>
	
	<body>
		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				
				<div class="h3 mt-4">Listagem</div>
			<?php echo '	<?php 
				mysqli_select_db($conexao,$database_conexao);
				
				$sql = "SELECT  ';
				
				for($i=1;$i<($qtLinhas-1);$i++){
							$campo = $camposNome[$i];
							echo $campo.", ";
				}
				echo $camposNome[$qtLinhas-1];
				echo ' FROM '.$nomeTabela.' where 1";
				$Recordset1 = mysqli_query($conexao,$sql) or die(mysqli_error($conexao));
				$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
				$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
			?>
';?>

					<table class="table table-striped table-hover">
						<thead>
							<tr>
					<?php
						//	$nomeTabela = $valor[1];
						//	$camposNome[]="";
						//	$camposTipo[]="";
						//	$camposTam[]="";
						
						for($i=1;$i<$qtLinhas;$i++){
							$campo = $camposNome[$i];?>
								<th><?php echo $campo;?></th>
						<?php } ?>								
								<th> </th>
							</tr>
						</thead>
						<tbody>
							<?php echo '<?php do { ?>';?>
								<tr>
								<?php for($i=1;$i<$qtLinhas;$i++){ 
									$campo = $camposNome[$i];
									$tipo = $camposTipo[$i];
									?>
									<td>
										<?php 
											if($tipo=='int' or $tipo=='char' or $tipo=='varchar'){ 
											echo '<?php echo htmlentities($row_Recordset1[\''.$campo.'\']); ?>';
											}
											if($tipo=='date'){ 
											echo '<?php echo date("d/m/Y",strtotime(htmlentities($row_Recordset1[\''.$campo.'\']))); ?>';
											}
											
											?>
											</td>
								<?php } ?>	
								<td>
										<span style="font-size: 21px; cursor:pointer;" class="fa-edit fa fa2" onClick="editar(<?php echo '<?php echo $row_Recordset1[\''.$camposNome[1].'\']; ?>';?>)"></span>
									</td>
								</tr>
							<?php echo '<?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>';?>
						</tbody>
					</table>
					
				</div>
			</div>
		</div>
		

	</body>
</html>