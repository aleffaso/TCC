<!DOCTYPE html>
    <html>
        <head>
            <meta charset=utf-8">
            <title>NodeMCU e MySQL</title>

        </head>

        <body>

            <form action="" method="POST">

                <input type="date" name="data">
                <input type="submit" name="submit">

            </form>

                <?php

                include('conexao.php');

                if($_SERVER['REQUEST_METHOD'] == "POST"){
                   //echo "<h1> recebeu a data: " .  $_POST['data'] . "</h1>";

                    $dataPesquisa = $_POST['data'];

                    $dataArray= explode("-", $dataPesquisa);

                    $dataPesquisa = $dataArray[0] . "-" . $dataArray[1] . "-" . $dataArray[2];


                    $sql = "SELECT * FROM tbdados WHERE data LIKE '%" . $dataPesquisa . "%'";



                }else{
                    echo "<h1>  Nao recebeu nada, vai mostrar o mes atual</h1>";

                    $dataAtual = date('Y-m');

                    echo "Mes atual: " . $dataAtual;

                    $sql = "SELECT * FROM tbdados WHERE data LIKE '%" . $dataAtual . "%'";

                }

                $stmt = $PDO->prepare($sql);
                $stmt->execute();

                echo "<table border=\"1\">";

                echo "<tr> <th>Sensor1</th></tr>";


                while ($linha = $stmt->fetch(PDO::FETCH_OBJ)){

                    $timestamp = strtotime($linha->$data);
                    $dataTabela = date('d/m/Y H:i:s', $timestamp);

                    echo "<tr>";
                    echo "<td>" . $linha->sensor1 . "</td>";
                   // echo "<td>" . $linha->sensor2 . "</td>";
                   // echo "<td>" . $linha->sensor3 . "</td>";
                   // echo "<td>" . $dataTabela . "</td>";
                    echo "</tr>";


                }


                echo "</table>"


                ?>

        </body>

    </html>