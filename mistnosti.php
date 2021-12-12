<?php
    require_once("./db_connect.php");

    $pdo = DB::connect();
    $arrowUp = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
    </svg>';
    $arrowDown = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
                </svg>';
    $poradi = filter_input(INPUT_GET,"poradi");
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Seznam Místností</title>
</head>
<body>
<div class="container">
 <h1><a href="index.php">Prohlížeč Databáze</a></h1>
 <table class="table">
    <?php
    echo "<thead class='thead-dark'><tr><th>Název <a href='mistnosti.php?poradi=name_asc'>$arrowDown</a><a href='mistnosti.php?poradi=name_desc'>$arrowUp</a>
          </th><th>Číslo <a href='mistnosti.php?poradi=no_asc'>$arrowDown</a><a href='mistnosti.php?poradi=no_desc'>$arrowUp</a></th>
          <th>Telefon <a href='mistnosti.php?poradi=phone_asc'>$arrowDown</a><a href='mistnosti.php?poradi=phone_desc'>$arrowUp</a></th></tr></thead>";
        switch($poradi)
        {
        case "name_asc":$stmt = $pdo -> query('SELECT * FROM room ORDER BY name ASC'); break;
        case "no_asc":$stmt = $pdo -> query('SELECT * FROM room ORDER BY no ASC'); break;
        case "phone_asc":$stmt = $pdo -> query('SELECT * FROM room ORDER BY phone ASC'); break;
        case "name_desc":$stmt = $pdo -> query('SELECT * FROM room ORDER BY name DESC'); break;
        case "no_desc":$stmt = $pdo -> query('SELECT * FROM room ORDER BY no DESC'); break;
        case "phone_desc":$stmt = $pdo -> query('SELECT * FROM room ORDER BY phone DESC'); break;    
        default: $stmt = $pdo -> query('SELECT * FROM room'); break;
        }
    foreach($stmt as $row)
    {
       echo "<tr><td><a href='kartaMistnosti.php?idMistnosti=$row[room_id]'>$row[name]</td><td>$row[no]</td>";
       if($row['phone'] != NULL)
       {
        echo  "<td>$row[phone]</td>";
       }
       else echo "<td>-</td>";
       echo "</tr>";
    }
    ?>
</table>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>