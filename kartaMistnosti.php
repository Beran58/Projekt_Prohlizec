<?php
    require_once("./db_connect.php");

    $pdo = DB::connect();

    $idMistnosti = filter_input(INPUT_GET,"idMistnosti",FILTER_VALIDATE_INT);
    if($idMistnosti == NULL)
    {
        echo "<h1>Bad Request</h1>";
        echo "<a href='mistnosti.php'>Zpět na seznam místností</a>";
        exit();
    }
    $query = "SELECT * FROM room WHERE room_id=?";
    $stmtRoom = $pdo -> prepare($query);
    $stmtRoom -> execute([$idMistnosti]);

    $query = "SELECT * FROM `key`";
    $stmtKey = $pdo->prepare($query);
    $stmtKey -> execute();
    $keys = []; 

    $query = "SELECT * FROM employee";
    $stmtEmployee = $pdo -> prepare($query);
    $stmtEmployee -> execute();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <?php
     $room = $stmtRoom -> fetch(PDO::FETCH_ASSOC);
     $room_name = $room['name'];
     echo "<title>Karta Místnosti $room_name</title>" ?>
</head>
<body>
<div class="container">
 <?php

        if($stmtRoom -> rowCount()===0)
        {
            echo "<h1>Not Found</h1>";
            echo "<a href='mistnosti.php'>Zpět na seznam místností</a>";
            exit();
        }

        echo "<h1><a href='index.php'>Prohlížeč Databáze</a></h1>";

        echo "<table class='table-small'>";

        while(($row = $stmtKey->fetch(PDO::FETCH_ASSOC)))
            {
                if($row['room'] === $idMistnosti)
                    {
                        array_push($keys,$row['employee']);
                    }
            }
            echo "<h3>Karta Místnosti č.$room[no]</h3>";
            echo "<tr><td class='text-right'><b>Číslo:</b></td><td> $room[no]</td></tr>";
            echo "<tr><td class='text-right'><b>Název:</b></td><td> $room[name]</td></tr>";
            echo "<tr><td class='text-right'><b>Telefon:</b></td><td> $room[phone]</td></tr>";
        $inc = 0;
        $wageSum = 0;
        $key_holders = [];
        $key_holders_id = [];
        while(($row = $stmtEmployee->fetch(PDO::FETCH_ASSOC)))
        {
            $init = substr($row['name'],0,1) . ". " . $row['surname'];
            if($row['room'] === $idMistnosti)
            {
                if($inc === 0)
                {
                    echo "<tr><td class='text-right'><b>Lidé:</b></td><td><a href='kartaZamestnance.php?idZamestnance=$row[employee_id]'>$init</a></td></tr>";
                }
                else echo "<tr><td>&nbsp;</td><td><a href='kartaZamestnance.php?idZamestnance=$row[employee_id]'>$init</a></td></tr>";
                $wageSum = $wageSum + $row['wage'];
                $inc++;
            }
            foreach($keys as $item)
            {
                if($item === $row['employee_id'])
                {
                    array_push($key_holders,$init);
                    array_push($key_holders_id,$row['employee_id']);
                }
            }
        }
            if($inc > 0)
            {
            $wageAvg = $wageSum/$inc;
            }
            else $wageAvg = "-";
            echo "<tr><td class='text-right'><b>Prům. Mzda:</b></td><td>$wageAvg</td></tr>";
            foreach($key_holders as $index => $item)
            {
                if($index === 0)
                {
                    echo "<tr><td class='text-right'><b>Klíče:</b></td><td><a href='kartaZamestnance.php?idZamestnance=$key_holders_id[$index]'>$item</a></td></tr>";
                }
                else echo "<tr><td>&nbsp;</td><td><a href='kartaZamestnance.php?idZamestnance=$key_holders_id[$index]'>$item</a></td></tr>";
            }
    echo "</table>";
 ?>
 <br>
 <a href="mistnosti.php">Zpět na seznam místností</a>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>