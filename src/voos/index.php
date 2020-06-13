<?php

require_once "../autenticacao.php";
require_once "../database.php";

$sth = $pdo->prepare("SELECT voos.id, date_format(data, '%d/%m/%Y') data, origem.nome origem, destino.nome destino, pilotos.nome comandante from voos
left join pilotos on pilotos.id = voos.comandante
left join pistas origem on origem.id = voos.origem
left join pistas destino on destino.id = voos.destino
where voos.ativo = 1; order by voos.id desc");
$sth->execute();
$voos = $sth->fetchAll();

$sth = $pdo->prepare("SELECT * from pilotos where ativo = 1 order by id desc");
$sth->execute();
$pilotos = $sth->fetchAll();

$sth = $pdo->prepare("SELECT * from pistas where ativo = 1 order by id desc");
$sth->execute();
$pistas = $sth->fetchAll();

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
                    <li class="active"><a href="index.php">Voos</a></li>
                </ul>
            </div>
		</div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
		<li><a href="../index.php">Home</a></li>
        <li><a href="../pilotos/index.php">Pilotos</a></li>
        <li><a href="../pistas/index.php">Pistas</a></li>
        <li><a href="../aeronaves/index.php">Aeronaves</a></li>
        <li class="active"><a href="index.php">Voos</a></li>
    </ul>

	<main>
		<div class="container">
			<div class="row">
				<div class="col m12">
					<h4 class="header">Voos</h4>
				</div>
			</div>

			<?php if(!empty($_GET['status']) && $_GET['status'] == 'salvo'): ?>
				<div id="page-warning" class="hide">
					<span>Voo salvo com sucesso <i class="right material-icons green-text">check</i></span>
				</div>
			<?php endif; ?>

			<?php if(!empty($_GET['status']) && $_GET['status'] == 'excluido'): ?>
				<div id="page-warning" class="hide">
					<span>Voo inativado com sucesso <i class="right material-icons green-text">check</i></span>
				</div>
			<?php endif; ?>     

			<div class="row">
				<div class="col s12">
					<table class="highlight">
						<thead>
							<tr>
								<th>ID</th>
								<th>Data</th>
								<th>Comandante</th>
								<th>Origem</th>
								<th>Destino</th>
								<th width="10%">
									<a class="waves-effect waves-light btn-floating btn-small modal-trigger green" href="#modal-novo"><i class="material-icons">add</i></a>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($voos as $item): ?>
								<tr>
									<td><?=$item['id']?></td>
									<td><?=$item['data']?></td>
									<td><?=$item['comandante']?></td>
									<td><?=$item['origem']?></td>
									<td><?=$item['destino']?></td>
									<td>
										<a class='dropdown-trigger btn-small btn-floating' href='#' data-target='item<?=$item['id']?>'><i class="material-icons">settings</i></a>

										<ul id='item<?=$item['id']?>' class='dropdown-content'>
											<li><a data-id='<?=$item['id']?>' class="modal-trigger editar" href="#modal-edicao">Editar</a></li>
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
	
	<div id="modal-novo" class="modal large">
		<form method="POST" action="save.php">
			<div class="modal-content">
				<h4>Novo</h4>
				<fieldset class="row">
					<legend>Ficha de Planejamento</legend>
					<div class="input-field col m3">
						<input required id="data" name="data" type="text" class="validate">
						<label for="data">Data</label>
					</div>
					<div class="input-field col m3">
						<select required id="comandante" name="comandante">
							<option value="0" disabled selected>-- Selecione --</option>
							<?php foreach($pilotos as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label for="comandante">Comandante</label>
					</div>
					<div class="input-field col m3">
						<select id="copiloto" name="copiloto">
							<option value="0" selected>-- Selecione --</option>
							<?php foreach($pilotos as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label for="copiloto">Copiloto</label>
					</div>
					<div class="input-field col m3">
						<select id="prevoo" name="prevoo">
							<option value="0" selected>-- Selecione --</option>
							<?php foreach($pilotos as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label for="prevoo">Pre Voo</label>
					</div>
					<div class="input-field col m3">
						<select id="posvoo" name="posvoo">
							<option value="0" selected>-- Selecione --</option>
							<?php foreach($pilotos as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label for="posvoo">Pos Voo</label>
					</div>
					<div class="input-field col m3">
						<select id="origem" name="origem">
							<option value="0" selected>-- Selecione --</option>
							<?php foreach($pistas as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label for="origem">Origem</label>
					</div>
					<div class="input-field col m3">
						<select id="destino" name="destino">
							<option value="0" selected>-- Selecione --</option>
							<?php foreach($pistas as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label for="destino">Destino</label>
					</div>
					<div class="input-field col m3">
						<select id="alternativa" name="alternativa">
							<option value="0" disabled selected>-- Selecione --</option>
							<?php foreach($pistas as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label for="alternativa">Alternativa</label>
					</div>
					<div class="input-field col m3">
						<input id="rumo" name="rumo" type="number" class="validate">
						<label for="rumo">Rumo</label>
					</div>
					<div class="input-field col m3">
						<input id="distancia" name="distancia" type="number" class="validate">
						<label for="distancia">Distancia</label>
					</div>
					<div class="input-field col m3">
						<input id="nivel" name="nivel" type="text" class="validate">
						<label for="nivel">Nivel</label>
					</div>
					<div class="input-field col m3">
						<input id="pob" name="pob" type="number" class="validate">
						<label for="pob">POB</label>
					</div>
				</fieldset>

				<fieldset class="row">
					<legend>Tempos</legend>
					<div class="input-field col m3">
						<input id="acionamento" name="acionamento" type="text" class="validate time time_inclusao">
						<label for="acionamento">Acionamento</label>
					</div>
					<div class="input-field col m3">
						<input id="corte" name="corte" type="text" class="validate time time_inclusao">
						<label for="corte">Corte</label>
					</div>
					<div class="input-field col m3">
						<input id="decolagem" name="decolagem" type="text" class="validate time time_inclusao">
						<label for="decolagem">Decolagem</label>
					</div>
					<div class="input-field col m3">
						<input id="pouso" name="pouso" type="text" class="validate time time_inclusao">
						<label for="pouso">Pouso</label>
					</div>
					<div class="input-field col m6">
						<input readonly id="tempo_voo_ca" name="tempo_voo_ca" type="text">
						<label for="tempo_voo_ca">Tempo acionado (corte-acionamento)</label>
					</div>
					<div class="input-field col m6">
						<input readonly id="tempo_voo_pd" name="tempo_voo_pd" type="text">
						<label for="tempo_voo_pd">Tempo de voo (pouso-decolagem)</label>
					</div>
					<div class="input-field col m6">
						<input readonly id="taxi" name="taxi" type="text">
						<label for="taxi">Taxi (decolagem-acionamento)</label>
					</div>
					<div class="input-field col m6">
						<input readonly id="tempo_voo_cp" name="tempo_voo_cp" type="text">
						<label for="tempo_voo_cp">Tempo de voo (corte-pouso)</label>
					</div>
				</fieldset>

				<fieldset class="row">
					<legend>Combustível (Litros)</legend>
					<div class="input-field col m3">
						<input id="combustivel_decolagem" name="combustivel_decolagem" type="number" class="validate">
						<label for="combustivel_decolagem">Decolagem</label>
					</div>
					<div class="input-field col m3">
						<input id="combustivel_pouso" name="combustivel_pouso" type="number" class="validate">
						<label for="combustivel_pouso">Pouso</label>
					</div>
					<div class="input-field col m3">
						<input id="combustivel_consumido" name="combustivel_consumido" type="number" class="validate">
						<label for="combustivel_consumido">Consumido</label>
					</div>
				</fieldset>

				<fieldset class="row">
					<legend>Rota</legend>
					<div class="input-field col m3">
						<input id="kmh" name="kmh" type="text" class="validate decimal">
						<label for="kmh">KM/H</label>
					</div>
					<div class="input-field col m3">
						<input id="gs" name="gs" type="number" class="validate">
						<label for="gs">GS</label>
					</div>
					<div class="input-field col m3">
						<input readonly id="fl" name="fl" type="text">
						<label class="active" for="fl">FL</label>
					</div>
				</fieldset>

				<fieldset class="row">
					<legend>Parâmetros</legend>
					<div class="input-field col m3">
						<input id="egt" name="egt" type="number" class="validate">
						<label for="egt">EGT</label>
					</div>
					<div class="input-field col m3">
						<input id="hp" name="hp" type="number" class="validate">
						<label for="hp">HP</label>
					</div>
					<div class="input-field col m3">
						<input id="torque" name="torque" type="number" class="validate">
						<label for="torque">Torque</label>
					</div>
					<div class="input-field col m3">
						<input id="volts" name="volts" type="text" class="validate decimal">
						<label for="volts">Volts</label>
					</div>
					<div class="input-field col m3">
						<input id="amperes" name="amperes" type="number" class="validate">
						<label for="amperes">Amperes</label>
					</div>
					<div class="input-field col m3">
						<input id="fuel_press" name="fuel_press" type="text" class="validate decimal">
						<label for="fuel_press">Fuel Press</label>
					</div>
					<div class="input-field col m3">
						<input id="oil_press" name="oil_press" type="number" class="validate">
						<label for="oil_press">Oil Press</label>
					</div>
					<div class="input-field col m3">
						<input id="oil_temp" name="oil_temp" type="number" class="validate">
						<label for="oil_temp">Oil Temp</label>
					</div>
					<div class="input-field col m3">
						<input id="ias" name="ias" type="number" class="validate">
						<label for="ias">IAS</label>
					</div>
					<div class="input-field col m3">
						<input id="tas" name="tas" type="number" class="validate">
						<label for="tas">TAS</label>
					</div>
					<div class="input-field col m3">
						<input id="n1" name="n1" type="text" class="validate decimal">
						<label for="n1">N1</label>
					</div>
					<div class="input-field col m3">
						<input id="fueld_flow" name="fueld_flow" type="number" class="validate">
						<label for="fueld_flow">Fuel Flow</label>
					</div>
					<div class="input-field col m3">
						<input id="oat" name="oat" type="number" class="validate">
						<label for="oat">OAT</label>
					</div>
				</fieldset>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="ativo" value="1" />
				<a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
				<button class="btn waves-effect waves-light green" type="submit"> Salvar
					<i class="material-icons right">send</i>
				</button>
			</div>
		</form>
	</div>

	<div id="modal-edicao" class="modal large">
		<form method="POST" action="save.php">
			<input type="hidden" name="id" id="edicao-id"/>
			<div class="modal-content">
				<h4>Alterar</h4>
				<fieldset class="row">
					<legend>Ficha de Planejamento</legend>
					<div class="input-field col m3">
						<input required id="data-edicao" name="data" type="text" class="validate">
						<label class="active" for="data">Data</label>
					</div>
					<div class="input-field col m3">
						<select required id="comandante-edicao" name="comandante">
							<option value="0">-- Selecione --</option>
							<?php foreach($pilotos as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label class="select-label" for="comandante">Comandante</label>
					</div>
					<div class="input-field col m3">
						<select id="copiloto-edicao" name="copiloto">
							<option value="0">-- Selecione --</option>
							<?php foreach($pilotos as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label class="select-label" for="copiloto">Copiloto</label>
					</div>
					<div class="input-field col m3">
						<select id="prevoo-edicao" name="prevoo">
							<option value="0">-- Selecione --</option>
							<?php foreach($pilotos as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label class="select-label" for="prevoo">Pre Voo</label>
					</div>
					<div class="input-field col m3">
						<select id="posvoo-edicao" name="posvoo">
							<option value="0">-- Selecione --</option>
							<?php foreach($pilotos as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label class="select-label" for="posvoo">Pos Voo</label>
					</div>
					<div class="input-field col m3">
						<select id="origem-edicao" name="origem">
							<option value="0">-- Selecione --</option>
							<?php foreach($pistas as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label class="select-label" for="origem">Origem</label>
					</div>
					<div class="input-field col m3">
						<select id="destino-edicao" name="destino">
							<option value="0">-- Selecione --</option>
							<?php foreach($pistas as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label class="select-label" for="destino">Destino</label>
					</div>
					<div class="input-field col m3">
						<select id="alternativa-edicao" name="alternativa">
							<option value="0">-- Selecione --</option>
							<?php foreach($pistas as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['nome']?></option>
							<?php endforeach; ?>
						</select>
						<label class="select-label" for="alternativa">Alternativa</label>
					</div>
					<div class="input-field col m3">
						<input id="rumo-edicao" name="rumo" type="number" class="validate">
						<label class="active" for="rumo">Rumo</label>
					</div>
					<div class="input-field col m3">
						<input id="distancia-edicao" name="distancia" type="number" class="validate">
						<label class="active" for="distancia">Distancia</label>
					</div>
					<div class="input-field col m3">
						<input id="nivel-edicao" name="nivel" type="text" class="validate">
						<label class="active" for="nivel">Nivel</label>
					</div>
					<div class="input-field col m3">
						<input id="pob-edicao" name="pob" type="number" class="validate">
						<label class="active" for="pob">POB</label>
					</div>
				</fieldset>

				<fieldset class="row">
					<legend>Tempos</legend>
					<div class="input-field col m3">
						<input id="acionamento-edicao" name="acionamento" type="text" class="validate time time_alteracao">
						<label class="active" for="acionamento">Acionamento</label>
					</div>
					<div class="input-field col m3">
						<input id="corte-edicao" name="corte" type="text" class="validate time time_alteracao">
						<label class="active" for="corte">Corte</label>
					</div>
					<div class="input-field col m3">
						<input id="decolagem-edicao" name="decolagem" type="text" class="validate time time_alteracao">
						<label class="active" for="decolagem">Decolagem</label>
					</div>
					<div class="input-field col m3">
						<input id="pouso-edicao" name="pouso" type="text" class="validate time time_alteracao">
						<label class="active" for="pouso">Pouso</label>
					</div>
					<div class="input-field col m6">
						<input readonly id="tempo_voo_ca-edicao" name="tempo_voo_ca" type="text">
						<label class="active" for="tempo_voo_ca">Tempo acionado (corte-acionamento)</label>
					</div>
					<div class="input-field col m6">
						<input readonly id="tempo_voo_pd-edicao" name="tempo_voo_pd" type="text">
						<label class="active" for="tempo_voo_pd">Tempo de voo (pouso-decolagem)</label>
					</div>
					<div class="input-field col m6">
						<input readonly id="taxi-edicao" name="taxi" type="text">
						<label class="active" for="taxi">Taxi (decolagem-acionamento)</label>
					</div>
					<div class="input-field col m6">
						<input readonly id="tempo_voo_cp-edicao" name="tempo_voo_cp" type="text">
						<label class="active" for="tempo_voo_cp">Tempo de voo (corte-pouso)</label>
					</div>
				</fieldset>

				<fieldset class="row">
					<legend>Combustível (Litros)</legend>
					<div class="input-field col m3">
						<input id="combustivel_decolagem-edicao" name="combustivel_decolagem" type="number" class="validate">
						<label class="active" for="combustivel_decolagem">Decolagem</label>
					</div>
					<div class="input-field col m3">
						<input id="combustivel_pouso-edicao" name="combustivel_pouso" type="number" class="validate">
						<label class="active" for="combustivel_pouso">Pouso</label>
					</div>
					<div class="input-field col m3">
						<input id="combustivel_consumido-edicao" name="combustivel_consumido" type="number" class="validate">
						<label class="active" for="combustivel_consumido">Consumido</label>
					</div>
				</fieldset>

				<fieldset class="row">
					<legend>Rota</legend>
					<div class="input-field col m3">
						<input id="kmh-edicao" name="kmh" type="text" class="validate decimal">
						<label class="active" for="kmh">KM/H</label>
					</div>
					<div class="input-field col m3">
						<input id="gs-edicao" name="gs" type="number" class="validate">
						<label class="active" for="gs">GS</label>
					</div>
					<div class="input-field col m3">
						<input readonly id="fl-edicao" name="fl" type="text" class="validate">
						<label class="active" for="fl">FL</label>
					</div>
				</fieldset>

				<fieldset class="row">
					<legend>Parâmetros</legend>
					<div class="input-field col m3">
						<input id="egt-edicao" name="egt" type="number" class="validate">
						<label class="active" for="egt">EGT</label>
					</div>
					<div class="input-field col m3">
						<input id="hp-edicao" name="hp" type="number" class="validate">
						<label class="active" for="hp">HP</label>
					</div>
					<div class="input-field col m3">
						<input id="torque-edicao" name="torque" type="number" class="validate">
						<label class="active" for="torque">Torque</label>
					</div>
					<div class="input-field col m3">
						<input id="volts-edicao" name="volts" type="text" class="validate decimal">
						<label class="active" for="volts">Volts</label>
					</div>
					<div class="input-field col m3">
						<input id="amperes-edicao" name="amperes" type="number" class="validate">
						<label class="active" for="amperes">Amperes</label>
					</div>
					<div class="input-field col m3">
						<input id="fuel_press-edicao" name="fuel_press" type="text" class="validate decimal">
						<label class="active" for="fuel_press">Fuel Press</label>
					</div>
					<div class="input-field col m3">
						<input id="oil_press-edicao" name="oil_press" type="number" class="validate">
						<label class="active" for="oil_press">Oil Press</label>
					</div>
					<div class="input-field col m3">
						<input id="oil_temp-edicao" name="oil_temp" type="number" class="validate">
						<label class="active" for="oil_temp">Oil Temp</label>
					</div>
					<div class="input-field col m3">
						<input id="ias-edicao" name="ias" type="number" class="validate">
						<label class="active" for="ias">IAS</label>
					</div>
					<div class="input-field col m3">
						<input id="tas-edicao" name="tas" type="number" class="validate">
						<label class="active" for="tas">TAS</label>
					</div>
					<div class="input-field col m3">
						<input id="n1-edicao" name="n1" type="text" class="validate decimal">
						<label class="active" for="n1">N1</label>
					</div>
					<div class="input-field col m3">
						<input id="fueld_flow-edicao" name="fueld_flow" type="number" class="validate">
						<label class="active" for="fueld_flow">Fuel Flow</label>
					</div>
					<div class="input-field col m3">
						<input id="oat-edicao" name="oat" type="number" class="validate">
						<label class="active" for="oat">OAT</label>
					</div>
				</fieldset>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="ativo" value="1" />
				<a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
				<button class="modal-close btn waves-effect waves-light green" type="submit">Salvar
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
<script type="text/javascript" src="../custom/imask.js"></script>
<script type="text/javascript" src="voos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	M.AutoInit();
	page.init();
});
</script>

</html>