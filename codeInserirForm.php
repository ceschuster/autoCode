<?php
	$sql = trim($_POST["sql"]);
	
	$linhas = explode("\n",$sql);
	$qtLinhas = sizeof($linhas)-1;
	
	
	$nomeTabela = "";
	preg_match('/`([^"]+)`/', trim($linhas[0]), $valor);
	
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
	//	FORMULÁRIO PARA INSERÇÃO DE DADOS
	//
	//
	
//echo '<!--'.$nomeTabela.'_insere.php -->';	
//echo "\n";

$txt='<?php 
if (!isset($_SESSION)) {
	session_start();
} 


if ((isset($_POST["acao"])) && ($_POST["acao"] == "Enviar")) {

	require_once("conexao.php");
	
	// previnir Spoofing de formulário. Ocorre quando alguém faz uma postagem 
	// em um de seus formulários de algum local inesperado 
	// prvinir Cross-Site Request Forgeries (ataques CSRF)
	
	if(!isset($_SESSION["token"]) or $_SESSION["token"]<>$_POST["token"]) die("invalid token");

	// campos vazios - manter apenas  os que são Required'."\r";

	for($i=1;$i<$qtLinhas;$i++){
		$campo = $camposNome[$i];
		$txt .= '	//if(!isset($_POST["'.$campo.'"]))  die("Campo \"'.$campo.'\" não preenchido.");'."\r";
	}
	
	
	$txt .= "\r";
	$txt .= "	// previnir sql injection\r";
	for($i=1;$i<$qtLinhas;$i++){
	$campo = $camposNome[$i];
	$txt .= '	$'.$campo.' = mysqli_real_escape_string($conexao, trim($_POST["'.$campo.'"]));'."\r";
	}
	
	$txt .= "\r	// Em campos numéricos, se a casa decimal vier com vírgula 
	// Ex.: 2001,32 
	// Deve-se trocar a vírgula por ponto:\r";
	for($i=1;$i<$qtLinhas;$i++){
	$campo = $camposNome[$i];
	$tipo = $camposTipo[$i];
	
	if($tipo=='float' or $tipo=='double' or $tipo=='real') {
		$txt .= '	$'.$campo.' = str_replace(",",".",str_replace(".","",$'.$campo.'));'."\r";
		}
	}

	
	$txt .= "\r	// Em campos DATE no formato dd/mm/yyyy
	// Deve-se trocar por yyyy-mm-dd \r";
	for($i=1;$i<$qtLinhas;$i++){
	$campo = $camposNome[$i];
	$tipo = $camposTipo[$i];
	
	if($tipo=='date') {
		$txt .= '	$'.$campo.' = implode("-",array_reverse(explode("/",$'.$campo.')));;'."\r";
		}
	}

	
	
	$txt .= "\r";
	$txt .= '	//verificação de valores numéricos corretos'."\r";
	$txt .= '	//descomentar apenas para os valores requeridos'."\r";
	for($i=1;$i<$qtLinhas;$i++){
	$campo = $camposNome[$i];
	$tipo = $camposTipo[$i];
	if($tipo=='int' or $tipo=='float' or $tipo=='double' or $tipo=='real') {
		$txt .= '	//if(!is_numeric($'.$campo.') ) die("Valor inválido de '.$campo.'");'."\r";
		}
	}
	
	$txt .= "\r";
	$txt .='	$sql="INSERT INTO '.$nomeTabela.' (';
	for($i=1;$i<($qtLinhas-1);$i++){
		$txt .= $camposNome[$i].', ';
	} $txt .= $camposNome[$qtLinhas-1].')';
	
	$txt .= ' VALUES (';
	for($i=1;$i<($qtLinhas-1);$i++){
		$txt .= '\'$'.$camposNome[$i].'\', ';
	} $txt .= '\'$'.$camposNome[$qtLinhas-1].'\')";';
	
	
	$txt.="\r".'	echo $sql; exit;'."\r";
	$txt.='
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
				
					<?php echo '<?php if(isset($_SESSION["msg"]) and $_SESSION["msg"]=="ok") {?>';?>
					
						<div class="h2 alert alert-success">Registro Salvo</div>
					<?php echo '<?php } ?>';?>
					
					<?php echo '<?php if(isset($_SESSION["msg"]) and $_SESSION["msg"]=="err") {?>';?>
					
						<div class="h2 alert alert-danger">Ocorreu um problema. Dados não salvos.</div>
					<?php echo '<?php } ?>';?>
					
					
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
					<?php
						//	$nomeTabela = $valor[1];
						//	$camposNome[]="";
						//	$camposTipo[]="";
						//	$camposTam[]="";
						
						for($i=1;$i<$qtLinhas;$i++){
							$campo = $camposNome[$i];
							$tipo = $camposTipo[$i];
							$tam = $camposTam[$i];
						?>
						<?php	if($tipo=='char' or $tipo=='varchar') { ?>
						
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="<?php echo $campo;?>"><?php echo ucfirst($campo);?></label>
									<input type="text" class="form-control" name="<?php echo $campo;?>" id="<?php echo $campo;?>" maxlength="<?php echo $tam;?>">
									<!--<div class="valid-feedback">Looks good!</div>-->
									<!--<div class="invalid-feedback">Preencha este campo</div>-->
								</div>
							</div>
						<?php } ?>
						<?php	if(($tipo=='varchar' and $tam>120) or $tipo=='text') { ?>
						
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="<?php echo $campo;?>"><?php echo ucfirst($campo);?></label>
									<textarea class="form-control" name="<?php echo $campo;?>" id="<?php echo $campo;?>" rows="3" cols="100%" maxlength="<?php echo $tam;?>"></textarea>
									<!--<div class="valid-feedback">Looks good!</div>-->
									<!--<div class="invalid-feedback">Preencha este campo</div>-->
								</div>
							</div>
						<?php } ?>
						<?php	if($tipo=='int') { ?>
						
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="<?php echo $campo;?>"><?php echo ucfirst($campo);?></label>
									<input type="number" class="form-control" name="<?php echo $campo;?>" id="<?php echo $campo;?>">
								</div>
							</div>
						<?php } ?>
						<?php	if($tipo=='date') { ?>
						
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="<?php echo $campo;?>"><?php echo ucfirst($campo);?></label>
									<input type="text" class="form-control" name="<?php echo $campo;?>" id="<?php echo $campo;?>">
								</div>
							</div>
						<?php } ?>
						<?php	if($tipo=='datetime') { ?>
						
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="<?php echo $campo;?>"><?php echo ucfirst($campo);?></label>
									<input type="datetime-local" class="form-control" name="<?php echo $campo;?>" id="<?php echo $campo;?>">
								</div>
							</div>
						<?php } ?>
						<?php	if($tipo=='float' or $tipo=='double' or $tipo=='decimal') { ?>
						
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="<?php echo $campo;?>"><?php echo ucfirst($campo);?></label>
									<input type="text" class="form-control real" name="<?php echo $campo;?>" id="<?php echo $campo;?>">
								</div>
							</div>
						<?php } ?>
					<?php } ?>
					
						<button type="submit" name="acao" value="Enviar" class="btn btn-primary">Enviar</button>
						
						<input type="hidden" name="token" value="<?php echo '<?php echo $_SESSION["token"];?>';?>">
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
			
<?php		for($i=1;$i<$qtLinhas;$i++){
				$campo = $camposNome[$i];
				$tipo = $camposTipo[$i]; ?>
				<?php	if($tipo=='date') { ?>
				
			var picker = new Lightpick({
				field: document.getElementById('<?php echo $campo;?>'),
				onSelect: function(date){
					//document.getElementById('result-1').innerHTML = date.format('Do MMMM YYYY');
				}
			});
					
					<?php }
				}?>

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