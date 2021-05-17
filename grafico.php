<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gráfico  - ESP8266 + PHP + MYSQL (Versão 2)</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv='refresh' content='60' URL=''>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <!-- Google Chart -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

<?php
//Inclui a conexão com o BD
    include 'conexao.php';

//Faz o SELECT da tabela, usando Prepared Statements.
    $sql = "SELECT data, hora, sensor1 FROM tbdados";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($data, $hora, $sensor1);
    $stmt->store_result();

    //Cria o array primário
    $dados = array();

    //Laço dos dados
    while ($ln = $stmt->fetch()){
        //Cria o array secundário, onde estarão os dados.

        $temp_hora = array();
        $temp_hora[] = ((string) $hora);
        $temp_hora[] = ((float) $sensor1);

        //Recebe no array principal, os dados.
        $dados[] = ($temp_hora);
    }
    //Trasforma os dados em JSON
    $jsonTable = json_encode($dados);
    //echo $jsonTable;
?>

<h3 align="center">ESP8266 + MYSQL + PHP + GOOGLE CHART - V2</h3>
<!-- Div do Gráfico -->

    <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
    </body>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Horario');
            data.addColumn('number', 'sensor1');
            data.addRows( <?php echo $jsonTable ?>);

            var options = {
                title: 'Sensor 2',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>

    <body><h1 align="middle">
        <form id='submit-form'>
            <button class="btn btn-info" value="Input Button"><i class="fa fa-magic mr-1"></i>+</button>
            <button class="btn btn-info" value="Input Button"><i class="fa fa-magic mr-1"></i>-</button>
        </form>
    </h1>
    </body>


</html>