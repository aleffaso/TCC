<?php
    //Incluimos o código de conexão ao BD
    include 'conexao.php';

    if(!isset($_GET['sensor1']) or !is_numeric($_GET['sensor1'])){
      //  echo $_GET['sensor1'];
        $arr_json = [
            'Sucesso' => false,
            'HoraServidor' => date('Y-m-d H:i:s'),
            'ErrMsg' => 'Medida invalida',
        ];

        echo json_encode($arr_json);
        exit();

    }

    if(!isset($_GET['idDispositivo']) or empty($_GET['idDispositivo'])){
      //  echo $_GET['sensor1'];
        $arr_json = [
            'Sucesso' => false,
            'HoraServidor' => date('Y-m-d H:i:s'),
            'ErrMsg' => 'Dispositivo invalido',
        ];

        echo json_encode($arr_json);
        exit();

    }

    //echo $_GET['idDispositivo'];

    //Variável responsável por guardar o valor enviado pelo ESP8266
    $sensor1 = $_GET['sensor1'];
    $idDispositivo = $_GET['idDispositivo'];

    //Captura a Data e Hora do SERVIDOR (onde está hospedada sua página):
    $hora = date('H:i:s');
    $data = date('Y-m-d');



    //Insere no Banco de Dados, usando Prepared Statements.
    $sql = "INSERT INTO tbdados (IdDispositivo, data, hora, sensor1)  VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sssd',$idDispositivo, $data, $hora, $sensor1);

    $stmt->execute();

    $arr_json = [
        'Sucesso' => true,
        'HoraServidor' => date('Y-m-d H:i:s'),

    ];

    echo json_encode($arr_json);
?>