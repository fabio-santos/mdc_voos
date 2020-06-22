<?php

require_once "../autenticacao.php";
require_once "../database.php";

$safePost = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$insert = <<<INSERT
INSERT INTO voos (
data, comandante, copiloto, prevoo, posvoo, origem, destino, 
alternativa, rumo, distancia, nivel, pob, acionamento, decolagem, 
pouso, corte, combustivel_decolagem, combustivel_pouso, 
combustivel_consumido, ias, tas, gs, n1, egt, hp, torque, volts, 
amperes, fuel_press, oil_press, oil_temp, fueld_flow, oat, ativo,
carga_transportada, combustivel_abastecido, ce, fornecedor
) VALUES (
:data, :comandante, :copiloto, :prevoo, :posvoo, :origem, :destino, 
:alternativa, :rumo, :distancia, :nivel, :pob, :acionamento, :decolagem, 
:pouso, :corte, :combustivel_decolagem, :combustivel_pouso, 
:combustivel_consumido, :ias, :tas, :gs, :n1, :egt, :hp, :torque, :volts, 
:amperes, :fuel_press, :oil_press, :oil_temp, :fueld_flow, :oat, :ativo,
:carga_transportada, :combustivel_abastecido, :ce, :fornecedor
)
INSERT;

$udpate = <<<UPDATE
UPDATE voos SET
data=:data, comandante=:comandante, copiloto=:copiloto, prevoo=:prevoo, posvoo=:posvoo, origem=:origem, destino=:destino, 
alternativa=:alternativa, rumo=:rumo, distancia=:distancia, nivel=:nivel, pob=:pob, acionamento=:acionamento, decolagem=:decolagem, 
pouso=:pouso, corte=:corte, combustivel_decolagem=:combustivel_decolagem, combustivel_pouso=:combustivel_pouso, 
combustivel_consumido=:combustivel_consumido, ias=:ias, tas=:tas, gs=:gs, n1=:n1, egt=:egt, hp=:hp, torque=:torque, volts=:volts, 
amperes=:amperes, fuel_press=:fuel_press, oil_press=:oil_press, oil_temp=:oil_temp, fueld_flow=:fueld_flow, oat=:oat, ativo=:ativo,
carga_transportada=:carga_transportada, combustivel_abastecido=:combustivel_abastecido, ce=:ce, fornecedor=:fornecedor
WHERE id = :id
UPDATE;

$safePost['data'] = converteData($safePost['data']);
$safePost['n1'] = converteDecimal($safePost['n1']);
$safePost['volts'] = converteDecimal($safePost['volts']);
$safePost['fuel_press'] = converteDecimal($safePost['fuel_press']);
$safePost['carga_transportada'] = converteDecimal($safePost['carga_transportada']);

foreach($safePost as $key => $value) {
    if(empty($value)) {
        $safePost[$key] = null;
    }
}

if(empty($_POST['id'])){
    $stmt = $pdo->prepare($insert);
} else {
    $id = intval($_POST['id']);
    $stmt = $pdo->prepare($udpate);
    $stmt->bindParam(':id', $id);
}

$stmt->bindParam(':data', $safePost['data']);
$stmt->bindParam(':comandante', $safePost['comandante']);
$stmt->bindParam(':copiloto', $safePost['copiloto']);
$stmt->bindParam(':prevoo', $safePost['prevoo']);
$stmt->bindParam(':posvoo', $safePost['posvoo']);
$stmt->bindParam(':origem', $safePost['origem']);
$stmt->bindParam(':destino', $safePost['destino']);
$stmt->bindParam(':alternativa', $safePost['alternativa']);
$stmt->bindParam(':rumo', $safePost['rumo']);
$stmt->bindParam(':distancia', $safePost['distancia']);
$stmt->bindParam(':nivel', $safePost['nivel']);
$stmt->bindParam(':pob', $safePost['pob']);
$stmt->bindParam(':acionamento', $safePost['acionamento']);
$stmt->bindParam(':decolagem', $safePost['decolagem']);
$stmt->bindParam(':pouso', $safePost['pouso']);
$stmt->bindParam(':corte', $safePost['corte']);
$stmt->bindParam(':combustivel_decolagem', $safePost['combustivel_decolagem']);
$stmt->bindParam(':combustivel_pouso', $safePost['combustivel_pouso']);
$stmt->bindParam(':combustivel_consumido', $safePost['combustivel_consumido']);
$stmt->bindParam(':ias', $safePost['ias']);
$stmt->bindParam(':tas', $safePost['tas']);
$stmt->bindParam(':gs', $safePost['gs']);
$stmt->bindParam(':n1', $safePost['n1']);
$stmt->bindParam(':egt', $safePost['egt']);
$stmt->bindParam(':hp', $safePost['hp']);
$stmt->bindParam(':torque', $safePost['torque']);
$stmt->bindParam(':volts', $safePost['volts']);
$stmt->bindParam(':amperes', $safePost['amperes']);
$stmt->bindParam(':fuel_press', $safePost['fuel_press']);
$stmt->bindParam(':oil_press', $safePost['oil_press']);
$stmt->bindParam(':oil_temp', $safePost['oil_temp']);
$stmt->bindParam(':fueld_flow', $safePost['fueld_flow']);
$stmt->bindParam(':oat', $safePost['oat']);
$stmt->bindParam(':ativo', $safePost['ativo']);
$stmt->bindParam(':carga_transportada', $safePost['carga_transportada']);
$stmt->bindParam(':combustivel_abastecido', $safePost['combustivel_abastecido']);
$stmt->bindParam(':ce', $safePost['ce']);
$stmt->bindParam(':fornecedor', $safePost['fornecedor']);

$stmt->execute();

function converteData($string) {
    $pieces = explode('/', $string);

    if(count($pieces) != 3) {
        header("location: index.php?status=datainvalida");
    }

    $dia = intval($pieces[0]);
    $mes = intval($pieces[1]);
    $ano = intval($pieces[2]);

    if($dia == 0 || $mes == 0 || $ano == 0) {
        header("location: index.php?status=datainvalida");
    }

    return sprintf("%s-%s-%s", $ano, $mes, $dia);
}

function converteDecimal($string) {
    return str_replace(',', '.', $string);
}

header("location: index.php?status=salvo");

?>