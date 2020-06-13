<?php

require_once "../database.php";

if(empty($_GET['id'])) {
    echo json_encode("ID não informado");
}

$id = intval($_GET['id']);

$sth = $pdo->prepare("SELECT date_format(data, '%d/%m/%Y') data, comandante, copiloto, prevoo, posvoo, origem, destino, 
alternativa, rumo, distancia, nivel, pob, time_format(acionamento, '%H:%i') acionamento, 
time_format(decolagem, '%H:%i') decolagem, 
time_format(pouso, '%H:%i') pouso,
time_format(corte, '%H:%i') corte,
kmh, combustivel_decolagem, combustivel_pouso, 
combustivel_consumido, ias, tas, gs, n1, egt, hp, torque, volts, 
amperes, fuel_press, oil_press, oil_temp, fueld_flow, oat from voos where id = {$id}");
$sth->execute();
$voo = $sth->fetchAll();

echo json_encode($voo[0]);

?>