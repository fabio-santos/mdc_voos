<?php
require_once "autenticacao.php";
require_once "database.php";

$sth = $pdo->prepare("SELECT count(*) count from pistas where ativo = 1 order by id desc");
$sth->execute();
$pistas = $sth->fetchAll();
$pistas = $pistas[0]['count'];

$sth = $pdo->prepare("SELECT count(*) count from pilotos where ativo = 1 order by id desc");
$sth->execute();
$pilotos = $sth->fetchAll();
$pilotos = $pilotos[0]['count'];

$sth = $pdo->prepare("SELECT count(*) count from aeronaves where ativo = 1 order by id desc");
$sth->execute();
$aeronaves = $sth->fetchAll();
$aeronaves = $aeronaves[0]['count'];

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="materialize/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="custom/custom.css"  media="screen,projection"/>  
     <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>
	<nav>
		<div class="nav-wrapper">
			<div class="nav-wrapper">
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul id="nav-mobile" class="left hide-on-med-and-down">
                    <li class="active"><a href="index.php">Home</a></li>
                    <li><a href="pilotos/index.php">Pilotos</a></li>
                    <li><a href="pistas/index.php">Pistas</a></li>
                    <li><a href="aeronaves/index.php">Aeronaves</a></li>
                    <li><a href="voos/index.php">Voos</a></li>
                    <li><a href="fornecedores/index.php">Fornecedores</a></li>
                </ul>
            </div>
		</div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
        <li class="active"><a href="index.php">Home</a></li>
        <li><a href="pilotos/index.php">Pilotos</a></li>
        <li><a href="pistas/index.php">Pistas</a></li>
        <li><a href="aeronaves/index.php">Aeronaves</a></li>
        <li><a href="voos/index.php">Voos</a></li>
        <li><a href="fornecedores/index.php">Fornecedores</a></li>
    </ul>

	<main>
		<div class="container">
			<div class="row">
				<div class="col m12">
					<h4 class="header">Home</h4>
				</div>
			</div>    
            <div class="row">
				<div class="col s12 m4">
                    <div class="card-panel deep-orange lighten-1">
                        <div class="row">
                            <div class="col s8">
                                <span class="white-text"><h5><?=$pistas?> Pistas</h5></span>
                            </div>
                            <div class="col s4">
                                <span class="white-text"><i class="large material-icons">location_on</i></span>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="col s12 m4">
                    <div class="card-panel lime lighten-1">
                        <div class="row">
                            <div class="col s8">
                                <span class="white-text"><h5><?=$pilotos?> Pilotos</h5></span>
                            </div>
                            <div class="col s4">
                                <span class="white-text"><i class="large material-icons">airline_seat_recline_extra</i></span>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="col s12 m4">
                    <div class="card-panel green lighten-1">
                        <div class="row">
                            <div class="col s8">
                                <span class="white-text"><h5><?=$aeronaves?> Aeronaves</h5></span>
                            </div>
                            <div class="col s4">
                                <span class="white-text"><i class="large material-icons">airplanemode_active</i></span>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</main>

</body>

<script type="text/javascript" src="materialize/js/materialize.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	M.AutoInit();
});
</script>

</html>