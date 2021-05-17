<?php


    $mysqli = new mysqli('localhost', 'root', 'root', 'bdnodemcu');


    if ($mysqli->connect_error) {
        die('Erro de conexão: (' . $mysqli->connect_errno . ')');
    }

    date_default_timezone_set('America/Sao_Paulo');

?>