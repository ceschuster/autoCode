<?php 
if (!isset($_SESSION)) {
	session_start();
} 


if ((isset($_POST["acao"])) && ($_POST["acao"] == "Enviar")) {

	require_once("conexao.php");
	
	// previnir Spoofing de formulário. Ocorre quando alguém faz uma postagem 
	// em um de seus formulários de algum local inesperado 
	// prvinir Cross-Site Request Forgeries (ataques CSRF)
	
	if(!isset($_SESSION["token"]) or $_SESSION["token"]<>$_POST["token"]) die("invalid token");

	// campos vazios - manter apenas  os que são Required
	//if(!isset($_POST["id_quarto"]))  die("Campo \"id_quarto\" não preenchido.");
	//if(!isset($_POST["dia"]))  die("Campo \"dia\" não preenchido.");
	//if(!isset($_POST["id_hotel"]))  die("Campo \"id_hotel\" não preenchido.");
	//if(!isset($_POST["tipo"]))  die("Campo \"tipo\" não preenchido.");
	//if(!isset($_POST["lotacao"]))  die("Campo \"lotacao\" não preenchido.");
	//if(!isset($_POST["valor"]))  die("Campo \"valor\" não preenchido.");
	//if(!isset($_POST["post_date"]))  die("Campo \"post_date\" não preenchido.");
	//if(!isset($_POST["qtde"]))  die("Campo \"qtde\" não preenchido.");
	//if(!isset($_POST["obs"]))  die("Campo \"obs\" não preenchido.");
	//if(!isset($_POST["status"]))  die("Campo \"status\" não preenchido.");

	// previnir sql injection
	$id_quarto = mysqli_real_escape_string($conexao, trim($_POST["id_quarto"]));
	$dia = mysqli_real_escape_string($conexao, trim($_POST["dia"]));
	$id_hotel = mysqli_real_escape_string($conexao, trim($_POST["id_hotel"]));
	$tipo = mysqli_real_escape_string($conexao, trim($_POST["tipo"]));
	$lotacao = mysqli_real_escape_string($conexao, trim($_POST["lotacao"]));
	$valor = mysqli_real_escape_string($conexao, trim($_POST["valor"]));
	$post_date = mysqli_real_escape_string($conexao, trim($_POST["post_date"]));
	$qtde = mysqli_real_escape_string($conexao, trim($_POST["qtde"]));
	$obs = mysqli_real_escape_string($conexao, trim($_POST["obs"]));
	$status = mysqli_real_escape_string($conexao, trim($_POST["status"]));

	// Em campos numéricos, se a casa decimal vier com vírgula 
	// Ex.: 2001,32 
	// Deve-se trocar a vírgula por ponto:
	$valor = str_replace(",",".",str_replace(".","",$valor));

	// Em campos DATE no formato dd/mm/yyyy
	// Deve-se trocar por yyyy-mm-dd 
	$dia = implode("-",array_reverse(explode("/",$dia)));;

	//verificação de valores numéricos corretos
	//descomentar apenas para os valores requeridos
	//if(!is_numeric($id_quarto) ) die("Valor inválido de id_quarto");
	//if(!is_numeric($id_hotel) ) die("Valor inválido de id_hotel");
	//if(!is_numeric($lotacao) ) die("Valor inválido de lotacao");
	//if(!is_numeric($valor) ) die("Valor inválido de valor");
	//if(!is_numeric($qtde) ) die("Valor inválido de qtde");

	$sql="INSERT INTO quartos (id_quarto, dia, id_hotel, tipo, lotacao, valor, post_date, qtde, obs, status) VALUES ('$id_quarto', '$dia', '$id_hotel', '$tipo', '$lotacao', '$valor', '$post_date', '$qtde', '$obs', '$status')";
	echo $sql; exit;

	$Result = mysqli_query($conexao, $sql) or die(mysqli_error($conexao));

	if($Result>=1) {
		$_SESSION["msg"]="ok";
	} else {
		$_SESSION["msg"]="error";
	}
	header("Location: index.php");

}

$_SESSION["token"] = md5(uniqid(rand(), true)); 


?>
<html>
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>quartos</title>
		
		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
		
		<link rel="stylesheet" type="text/css" href="lightpick.css">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		


		<script src="jquery.maskedinput.min.js"></script>
		<script src="jquery.maskMoney.js"></script>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
		<script src="lightpick.js"></script>
		<!-- https://wakirin.github.io/Lightpick/ -->

	</head>
	
	<body>
		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				
				<div class="h3 mt-4">Cadastro</div>
				<hr>

				<img id="espereOk" style="display: none;position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);transform: -webkit-translate(-50%, -50%);transform: -moz-translate(-50%, -50%);transform: -ms-translate(-50%, -50%);color:darkred; z-index: 9999;" class="float:right" src="_preloader.gif">
				
					<?php if(isset($_SESSION["msg"]) and $_SESSION["msg"]=="ok") {?>					
						<div class="h2 alert alert-success">Registro Salvo</div>
					<?php } ?>					
					<?php if(isset($_SESSION["msg"]) and $_SESSION["msg"]=="err") {?>					
						<div class="h2 alert alert-danger">Ocorreu um problema. Dados não salvos.</div>
					<?php } ?>					
					
					<form class="needs-validation" id="form1" method="POST" action="inserir.php" novalidate>
						<!-- exemplo de form com duas colunas -->
						<!--
							<div class="form-row">
							<div class="form-group col-md-4">
							<label>Campo 1</label>
							<input type="text" class="form-control" name="campo1" id="campo1">
							</div>
							<div class="form-group col-md-8">
							<label>Campo 2</label>
							<input type="text" class="form-control" name="campo2" id="campo2">
							</div>
							</div>
						-->
																													
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="id_quarto">Id_quarto</label>
									<input type="number" class="form-control" name="id_quarto" id="id_quarto">
								</div>
							</div>
																																																											
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="dia">Dia</label>
									<input type="text" class="form-control" name="dia" id="dia">
								</div>
							</div>
																																															
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="id_hotel">Id_hotel</label>
									<input type="number" class="form-control" name="id_hotel" id="id_hotel">
								</div>
							</div>
																																									
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="tipo">Tipo</label>
									<input type="text" class="form-control" name="tipo" id="tipo" maxlength="50">
									<!--<div class="valid-feedback">Looks good!</div>-->
									<!--<div class="invalid-feedback">Preencha este campo</div>-->
								</div>
							</div>
																																																																	
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="lotacao">Lotacao</label>
									<input type="number" class="form-control" name="lotacao" id="lotacao">
								</div>
							</div>
																																																																							
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="valor">Valor</label>
									<input type="text" class="form-control real" name="valor" id="valor">
								</div>
							</div>
																																															
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="post_date">Post_date</label>
									<input type="datetime-local" class="form-control" name="post_date" id="post_date">
								</div>
							</div>
																																									
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="qtde">Qtde</label>
									<input type="number" class="form-control" name="qtde" id="qtde">
								</div>
							</div>
																																															
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="obs">Obs</label>
									<textarea class="form-control" name="obs" id="obs" rows="3" cols="100%" maxlength="N/A"></textarea>
									<!--<div class="valid-feedback">Looks good!</div>-->
									<!--<div class="invalid-feedback">Preencha este campo</div>-->
								</div>
							</div>
																																															
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="status">Status</label>
									<input type="text" class="form-control" name="status" id="status" maxlength="3">
									<!--<div class="valid-feedback">Looks good!</div>-->
									<!--<div class="invalid-feedback">Preencha este campo</div>-->
								</div>
							</div>
																																														
						<button type="submit" name="acao" value="Enviar" class="btn btn-primary">Enviar</button>
						
						<input type="hidden" name="token" value="<?php echo $_SESSION["token"];?>">
					</form>
					
				</div>
			</div>
		</div>
		
		<script>
		  /*
				$('.cepA').mask("99.999-999"); 
				$('.cpf').mask('999.999.999-99', {reverse: false});
				$('.cnpj').mask('99.999.999/9999-99', {reverse: false});
				$('.money').mask('000.000,00', {reverse: false});
				$('.money2').mask("#.##0,00", {reverse: false});
				$(".brazilianDate").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
				$("#real").maskMoney({showSymbol:false,symbol:"R$", decimal:",", thousands:".", allowZero:true});
				$("#premioFinal").maskMoney({showSymbol:false, decimal:",", thousands:".", allowZero:true});
				
				$(function() {
				$(".real").maskMoney({showSymbol:false,symbol:"R$", decimal:",", thousands:".", allowZero:true});
				
				// Mascara Telefone
				$('.tel1').mask("(99) 9999-9999?9").ready(function(event) {
				var target, phone, element;
				target = (event.currentTarget) ? event.currentTarget : event.srcElement;
				phone = target.value.replace(/\D/g, '');
				element = $(target);
				element.unmask();
				if(phone.length > 10) {
				element.mask("(99) 99999-999?9");
				} else {
				element.mask("(99) 9999-9999?9");
				}
				});
				//Fim Mascara Telefone
				});
			*/
			$(".real").maskMoney({showSymbol:false,symbol:"R$", decimal:",", thousands:".", allowZero:true});
			
												
			var picker = new Lightpick({
				field: document.getElementById('dia'),
				onSelect: function(date){
					//document.getElementById('result-1').innerHTML = date.format('Do MMMM YYYY');
				}
			});
					
																																					
		/*		
			$("form").submit(function(e){
				e.preventDefault();
				var ok=1;
				
				valor = $("#campo").val();
				if(valor.length <=3){
					alert("Preencha o campo.");
				}
				if(ok==1) {
					$("#espereOk").show(); 
					this.submit();
				}
				//if(ok==0) {alert("Selecione pelo menos um quarto.");}
				
			});		
				*/
				
				// https://getbootstrap.com/docs/4.1/components/forms/#validation
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
								$("#espereOk").show(); 
								//event.preventDefault();
								//event.stopPropagation();
							}
							form.classList.add('was-validated');
						}, false);
					});
				}, false);
			})();				
		</script>
	</body>
	
</html>