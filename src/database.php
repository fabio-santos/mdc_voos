<?php

$credenciais = json_decode(file_get_contents(__DIR__ . "/banco.json"));

$dsn = sprintf("mysql:host=%s;dbname=%s", 
    $credenciais->host, 
    $credenciais->dbname
);

try {
    $pdo = new PDO($dsn, $credenciais->user, $credenciais->password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $utf8_query = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
    $sth = $pdo->prepare($utf8_query);
    $sth->execute();
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>