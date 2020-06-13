<?php

require_once "../autenticacao.php";
require_once "../database.php";

$safePost = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
$ativo = true;

if(empty($_POST['id'])){
    $stmt = $pdo->prepare("INSERT INTO pistas (nome, ativo) VALUES(:nome, :ativo)");
    $stmt->bindParam(':nome', $safePost['nome']);
    $stmt->bindParam(':ativo', $ativo);

    $stmt->execute();
} else {
    $id = intval($_POST['id']);
    $stmt = $pdo->prepare("UPDATE pistas SET nome=:nome, ativo=:ativo WHERE id = {$id}");
    $stmt->bindParam(':nome', $safePost['nome']);
    $stmt->bindParam(':ativo', $ativo);

    $stmt->execute();
}

header("location: index.php?status=salvo");

?>