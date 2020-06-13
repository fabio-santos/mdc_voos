<?php 

$credenciais = json_decode(file_get_contents(__DIR__ . "/senha.json"));

if (
    empty($_SERVER['PHP_AUTH_USER'])
    || $_SERVER['PHP_AUTH_USER'] != $credenciais->login
    || $_SERVER['PHP_AUTH_PW'] != $credenciais->senha
) {
    header('WWW-Authenticate: Basic realm="Credenciais"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Credenciais Incorretas';
    exit;
}

?>