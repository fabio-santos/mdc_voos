<?php

require_once "../autenticacao.php";
require_once "../database.php";

if(empty($_POST['id'])) {
    exit;
}

$ativo = 0;
$id = intval($_POST['id']);
$stmt = $pdo->prepare("UPDATE pilotos SET ativo=:ativo WHERE id = {$id}");
$stmt->bindParam(':ativo', $ativo);
$stmt->execute();

header("location: index.php?status=excluido");

?>