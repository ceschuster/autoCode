<html>
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>autoCode by Schuster</title>
		
		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
		
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</head>
	
	<body>
		<div class="container-fluid mt-2">
			<div class="row">
				
				<div class="col-md-4">Modelo SQL:<br>
					<!--<textarea id="sqlOriginal" style="width:90%" rows="15">
						CREATE TABLE `quartos` (
						`id_quarto` int(11) NOT NULL,
						`dia` date NOT NULL,
						`id_hotel` int(11) NOT NULL,
						`tipo` varchar(50) NOT NULL,
						`lotacao` int(11) NOT NULL,
						`valor` float(7,2) NOT NULL,
						`post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
						`qtde` int(11) NOT NULL,
						`obs` text NOT NULL DEFAULT '0',
						`status` char(3) NOT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;
						
					</textarea>
					-->
					<textarea id="sqlOriginal" style="width:90%" rows="15"></textarea>
					
					<br><input type="text" id="codeTipo">
					<br>
					
				</div>
				
				
				<div class="col-md-4">
					Saída 1<br>
					<textarea id="saida_1" style="width:100%" rows="15"></textarea>
					<br>
					<div onClick="criar1()" class="btn btn-secondary mt-2">Criar arquivo</div>
					<div id="ver1" onClick="ver1()" class="btn btn-secondary mt-2" style="display:none">Ver</div>
				</div>
				<div class="col-md-4">
					Saída 2<br>
					<textarea id="saida_2" style="width:100%" rows="15"></textarea>
					<br>
					<div onClick="criar1()" class="btn btn-secondary mt-2">Criar</div>
					<div id="ver1" onClick="ver1()" class="btn btn-secondary mt-2" style="display:none">Ver</div>
				</div>
				
			</div>
			
			
			<div class="row mt-4">
				<div class="col-md-2">
					<div onClick="inserirForm()" class="btn btn-primary mb-2">Formulário de inserção</div>
					<a target="_blank" href="inserirForm.jpg">
					<img src="inserirForm.jpg" class="img-fluid" style="border:solid 1px;"></a>
				</div>
				<div class="col-md-2">
					<div onClick="listagem()" class="btn btn-primary mb-2">Listagem</div>
					<a target="_blank" href="listar1.jpg">
					<img src="listar1.jpg" class="img-fluid" style="border:solid 1px;"></a>
				</div>
				<div class="col-md-2">
					<div class="h4">????</div><a target="_blank" href="nada.jpg">
					<img src="nada.jpg" class="img-fluid" style="border:solid 1px;"></a>
				</div>
				<div class="col-md-2">
					<div class="h4">????</div><a target="_blank" href="nada.jpg">
					<img src="nada.jpg" class="img-fluid" style="border:solid 1px;"></a>
				</div>
				<div class="col-md-2">
					<div class="h4">????</div><a target="_blank" href="nada.jpg">
					<img src="nada.jpg" class="img-fluid" style="border:solid 1px;"></a>
				</div>
				<div class="col-md-2">
					<div class="h4">????</div><a target="_blank" href="nada.jpg">
					<img src="nada.jpg" class="img-fluid" style="border:solid 1px;"></a>
				</div>
			</div>
			
			<div class="row mt-4">
				<div class="col-md-12">
					<hr>
					
					<form class="needs-validation" novalidate>
						<div class="form-row">
							<div class="col-md-4 mb-3">
								<label for="validationCustom01">First name</label>
								<input type="text" class="form-control" id="validationCustom01" placeholder="First name" value="" required>
								<div class="valid-feedback">Looks good!</div>
								<div class="invalid-feedback">Please choose a username.</div>
							</div>
							
						</div>
						
						<button class="btn btn-primary" type="submit">Submit form</button>
					</form>
					
					
				</div>
				
			</div>
			
		</body>
		
	</html>
	
	<script>
	
var sqlInsert="CREATE TABLE `quartos` (\n"+
						"`id_quarto` int(11) NOT NULL,\n"+
						"`dia` date NOT NULL,\n"+
						"`id_hotel` int(11) NOT NULL,\n"+
						"`tipo` varchar(50) NOT NULL,\n"+
						"`lotacao` int(11) NOT NULL,\n"+
						"`valor` float(7,2) NOT NULL,\n"+
						"`post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',\n"+
						"`qtde` int(11) NOT NULL,\n"+
						"`obs` text NOT NULL DEFAULT '0',\n"+
						"`status` char(3) NOT NULL\n"+
						") ENGINE=MyISAM DEFAULT CHARSET=latin1;";

						
var sqlList="CREATE TABLE `clientes` (\n"+
  "`id` int(11) NOT NULL,\n"+
  "`nome` varchar(50) NOT NULL,\n"+
  "`nascimento` date NOT NULL,\n"+
  "`status` char(3) NOT NULL\n"+
") ENGINE=MyISAM DEFAULT CHARSET=latin1;";


		function inserirForm(){
			$("#ver1").hide();
			$("#sqlOriginal").val(sqlInsert);
			$("#codeTipo").val("inserir1");
			
			sql = $("#sqlOriginal").val().trim();
			
			$.post("codeInserirForm.php", {sql: sql}, function(result){
				$("#saida_1").val(result);
			});
		}
		function listagem(){
			$("#ver1").hide();
			$("#sqlOriginal").val(sqlList);
			$("#codeTipo").val("listagem1");
			sql = $("#sqlOriginal").val().trim();
			
			$.post("codeListagem.php", {sql: sql}, function(result){
				$("#saida_1").val(result);
			});
		}
		
		function criar1(){
			conteudo = $("#saida_1").val();
			codeTipo = $("#codeTipo").val();
			if(codeTipo=="inserir1") {
				$.post("criaArq.php", {conteudo: conteudo, nome: "inserir.php"}, function(result){
					$("#ver1").show();
				});
			}
			if(codeTipo=="listagem1") {
				$.post("criaArq.php", {conteudo: conteudo, nome: "listar.php"}, function(result){
					$("#ver1").show();
				});
			}
		}
		function ver1(){
			codeTipo = $("#codeTipo").val();
			if(codeTipo=="inserir1") {window.open("arqs/inserir.php");}
			if(codeTipo=="listagem1") {window.open("arqs/listar.php");}
		}
		
	</script>
	
	<?php
		/*
			
			CREATE TABLE `quartos` (
			id_quarto` int(11) NOT NULL,
			`id_hotel` int(11) NOT NULL,
			`tipo` varchar(50) NOT NULL,
			`lotacao` int(11) NOT NULL,
			`valor` float(7,2) NOT NULL,
			`qtde` int(11) NOT NULL,
			`status` char(3) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			
		*/
		?>						
		
		<script>
			// Example starter JavaScript for disabling form submissions if there are invalid fields
			(function() {
				'use strict';
				window.addEventListener('load', function() {
					// Fetch all the forms we want to apply custom Bootstrap validation styles to
					var forms = document.getElementsByClassName('needs-validation');
					// Loop over them and prevent submission
					var validation = Array.prototype.filter.call(forms, function(form) {
						form.addEventListener('submit', function(event) {
							if (form.checkValidity() === false) {
								event.preventDefault();
								event.stopPropagation();
							}
							if (form.checkValidity() === true) {
								alert("aff");
							}
							
							form.classList.add('was-validated');
						}, false);
					});
				}, false);
			})();
		</script>			
		
		
		
