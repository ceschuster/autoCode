<?php 
if (!isset($_SESSION)) {
	session_start();
} 

require_once("conexao.php");

$_SESSION["token"] = md5(uniqid(rand(), true)); 


?>
<html>
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>clientes</title>
		
		
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
				<?php 
				mysqli_select_db($conexao,$database_conexao);
				
				$sql = "SELECT  id, nome, nascimento, status FROM clientes where 1";
				$Recordset1 = mysqli_query($conexao,$sql) or die(mysqli_error($conexao));
				$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
				$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
			?>

					<table class="table table-striped table-hover">
						<thead>
							<tr>
													<th>id</th>
														<th>nome</th>
														<th>nascimento</th>
														<th>status</th>
														
								<th> </th>
							</tr>
						</thead>
						<tbody>
							<?php do { ?>								<tr>
																	<td>
										<?php echo htmlentities($row_Recordset1['id']); ?>											</td>
																	<td>
										<?php echo htmlentities($row_Recordset1['nome']); ?>											</td>
																	<td>
										<?php echo date("d/m/Y",strtotime(htmlentities($row_Recordset1['nascimento']))); ?>											</td>
																	<td>
										<?php echo htmlentities($row_Recordset1['status']); ?>											</td>
									
								<td>
										<span style="font-size: 21px; cursor:pointer;" class="fa-edit fa fa2" onClick="editar(<?php echo $row_Recordset1['id']; ?>)"></span>
									</td>
								</tr>
							<?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>						</tbody>
					</table>
					
				</div>
			</div>
		</div>
		

	</body>
</html>