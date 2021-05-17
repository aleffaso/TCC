<?php

    date_default_timezone_set('America/Sao_Paulo');

    $data = date('Y-m-d H:i:s');


    $arr_json = [
        'Hora do servidor' => $data,
    ];


echo json_encode($arr_json);






?>