<?php

require_once "../autenticacao.php";
require_once "../database.php";

$sth = $pdo->prepare("SELECT * from fornecedores where ativo = 1 order by id desc");
$sth->execute();
$fornecedores = $sth->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../materialize/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="../custom/custom.css"  media="screen,projection"/>  
     <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>
	<nav>
		<div class="nav-wrapper">
			<div class="nav-wrapper">
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul id="nav-mobile" class="left hide-on-med-and-down">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../pilotos/index.php">Pilotos</a></li>
                    <li><a href="../pistas/index.php">Pistas</a></li>
                    <li><a href="../aeronaves/index.php">Aeronaves</a></li>
                    <li><a href="../voos/index.php">Voos</a></li>
					<li class="active"><a href="index.php">Fornecedores</a></li>
                </ul>
            </div>
		</div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../pilotos/index.php">Pilotos</a></li>
        <li><a href="../pistas/index.php">Pistas</a></li>
        <li><a href="../aeronaves/index.php">Aeronaves</a></li>
        <li><a href="../voos/index.php">Voos</a></li>
		<li class="active"><a href="index.php">Fornecedores</a></li>
    </ul>

	<main>
		<div class="container">
			<div class="row">
				<div class="col m12">
					<h4 class="header">Fornecedores</h4>
				</div>
			</div>

			<?php if(!empty($_GET['status']) && $_GET['status'] == 'salvo'): ?>
				<div id="page-warning" class="hide">
					<span>Fornecedor salvo com sucesso <i class="right material-icons green-text">check</i></span>
				</div>
			<?php endif; ?>

			<?php if(!empty($_GET['status']) && $_GET['status'] == 'excluido'): ?>
				<div id="page-warning" class="hide">
					<span>Fornecedor inativado com sucesso <i class="right material-icons green-text">check</i></span>
				</div>
			<?php endif; ?>     

			<div class="row">
				<div class="col s12">
					<table class="highlight">
						<thead>
							<tr>
								<th>Fornecedor</th>
								<th width="10%">
									<a class="waves-effect waves-light btn-floating btn-small modal-trigger green" href="#modal-novo"><i class="material-icons">add</i></a>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($fornecedores as $item): ?>
								<tr>
									<td><?=$item['nome']?></td>
									<td>
										<a class='dropdown-trigger btn-small btn-floating' href='#' data-target='item<?=$item['id']?>'><i class="material-icons">settings</i></a>

										<ul id='item<?=$item['id']?>' class='dropdown-content'>
											<li><a data-id='<?=$item['id']?>' data-name='<?=$item['nome']?>' class="modal-trigger editar" href="#modal-edicao">Editar</a></li>
											<li><a data-id='<?=$item['id']?>' class="modal-trigger excluir" href="#modal-exclusao">Excluir</a></li>
										</ul>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</main>
	
	<div id="modal-novo" class="modal">
		<form method="POST" action="save.php">
			<div class="modal-content">
				<h4>Novo</h4>
				<div class="row">
					<div class="input-field col s12">
						<input id="nome" name="nome" type="text" class="validate">
						<label for="nome">Fornecedores</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
				<button class="modal-close btn waves-effect waves-light green" type="submit" name="action">Salvar
					<i class="material-icons right">send</i>
				</button>
			</div>
		</form>
	</div>

	<div id="modal-edicao" class="modal">
		<form method="POST" action="save.php">
			<input type="hidden" name="id" id="edicao-id"/>
			<div class="modal-content">
				<h4>Alterar</h4>
				<div class="row">
					<div class="input-field col s12">
						<input id="nome-edicao" name="nome" type="text" class="validate">
						<label for="nome-edicao">Fornecedores</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
				<button class="modal-close btn waves-effect waves-light green" type="submit" name="action">Salvar
					<i class="material-icons right">send</i>
				</button>
			</div>
		</form>
	</div>
	
	<div id="modal-exclusao" class="modal">
		<form method="POST" action="delete.php">
			<input type="hidden" name="id" id="exclusao-id"/>
			<div class="modal-content">
				<h4>Exclusão</h4>
				<p>Deseja mesmo remover este item?</p>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
				<button class="modal-close btn waves-effect waves-light amber" type="submit" name="action">Confirmar
					<i class="material-icons right">send</i>
				</button>
			</div>
		</form>
	</div>

	<div id="carregando" class="modal">
		<div class="modal-content">
			<h4>Aguarde</h4>
			<div id="loader" class="progress">
                <div class="indeterminate"></div>
            </div>
		</div>
	</div>
</body>

<script type="text/javascript" src="../materialize/js/materialize.min.js"></script>
<script type="text/javascript" src="fornecedores.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	M.AutoInit();
	page.init();
});
</script>

</html>