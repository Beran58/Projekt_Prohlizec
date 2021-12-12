<?php
     require_once("./db_connect.php");

     $pdo = DB::connect();

    $idZamestnance = filter_input(INPUT_GET,"idZamestnance",FILTER_VALIDATE_INT);
    if($idZamestnance == NULL)
    {
        http_response_code(400);
        echo "<h1>Bad Request</h1>";
        echo "<a href='zamestnanci.php'>Zpět na seznam zaměstnanců</a>";
        exit();
    }

    $query = "SELECT e.*,r.room_id , r.name AS room_name FROM employee e INNER JOIN room r ON e.room = r.room_id WHERE employee_id=?";
    $stmtEmployee = $pdo -> prepare($query);
    $stmtEmployee -> execute([$idZamestnance]);

    $query = "SELECT * FROM `key` WHERE employee=?";
    $stmtKey = $pdo -> prepare($query);
    $stmtKey -> execute([$idZamestnance]);

    $query = "SELECT * FROM room";
    $stmtRoom = $pdo -> prepare($query);
    $stmtRoom -> execute();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <?php
        $employee = $stmtEmployee->fetch(PDO::FETCH_ASSOC);
        $employee_name = $employee['surname'];
        echo "<title>Karta Zaměstnance $employee_name</title>";
    ?>
</head>
<body>
<div class="container">
 <?php
    if($stmtEmployee -> rowCount()=== 0)
    {
        http_response_code(404);
        echo "<h1>Not Found</h1>";
        echo "<a href='zamestnanci.php'>Zpět na seznam zaměstnanců</a>";
        exit();
    }
    echo " <h1 ><a href='index.php'>Prohlížeč Databáze</a></h1>";

     $init = substr($employee['name'],0,1) . ".";  
      echo "<h3>Karta Zaměstnance $employee[surname] $init</h3>";
        echo " <table class='table-small'>
        <tr><td class='text-right'><b>Jméno:</b></td><td> $employee[name]</td></tr>
        <tr><td class='text-right'><b>Přijmení:</b></td><td> $employee[surname]</td></tr>
        <tr><td class='text-right'><b>Pozice:</b></td><td> $employee[job]</td></tr>
        <tr><td class='text-right'><b>Mzda:</b></td><td> $employee[wage]</td></tr>
        <tr><td class='text-right'><b>Místnost:</b></td><td><a href='kartaMistnosti.php?idMistnosti=$employee[room_id]'> $employee[room_name]</a></td></tr>";

    $keys = [];

    while(($row = $stmtKey->fetch(PDO::FETCH_ASSOC)) !== false)
    {
        array_push($keys,$row['room']);
    }
    
    while(($row = $stmtRoom->fetch(PDO::FETCH_ASSOC)) !== false)
    {
        foreach($keys as $index => $item)
        {
            if($item === $row['room_id'])
            {
                if($index === 0)
                {
                    echo "<tr><td class='text-right'><b>Klíče:</b></td><td><a href='kartaMistnosti.php?idMistnosti=$row[room_id]'>$row[name]</td></tr>";
                }
                else echo "<tr><td>&nbsp;</td><td><a href='kartaMistnosti.php?idMistnosti=$row[room_id]'>$row[name]</td></tr>";
            }
        }
    }
 ?>
 </table>
 <br>
 <a href="zamestnanci.php">Zpět na seznam zaměstnanců</a>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>