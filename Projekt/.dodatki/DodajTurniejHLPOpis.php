<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8">
    <head>
        <title>Turnieje</title>
        <link rel="stylesheet" href="TurniejStyle.css" type="text/css">
    </head>
    <body>
        <p align = center>Panel przebiegu partii </p>
        <div class = "bard" align = center>Cóż to była za partia! <br> Dodaj zapis jej przebiegu, 
        <br> by bardowie mieli co opiewać w pieśniach!</div>
    </body>
</html>
<?php
    session_start();    
    $conn = oci_pconnect($_SESSION['LOGIN'], $_SESSION['PASSWORD'], "//labora.mimuw.edu.pl/LABS");

    if (!$conn) {
        echo "Nie udało się połączyć z bazą danych, spróbuj ponownie\n";
        $e = oci_error();
        echo $e['message'];
    }

    $_SESSION['changed'] = $_POST['changed'];
    $_SESSION['winner'] = $_POST['winner'];
    $loser = $_SESSION['wins'][$_SESSION['changed'] * 2 + 1];

    if ($loser == $_SESSION['winner']) {
        $loser = $_SESSION['wins'][$_SESSION['changed'] * 2];
    }

    if ($_SESSION['changed'] != 0 && $_SESSION['wins'][intdiv($_SESSION['changed'], 2)] == - 1 && $loser != -1) { 
        echo "<span align = center>";
        echo "<form action=\"./DodajTurniejHLP.php\" method = \"post\">";
        echo "<input type=\"text\" name=\"description\" >";
        echo "<input type=\"submit\" name=\"submit\" value= 'Dodaj opis' 
        style = \"color:white;border:0px #000 solid;background-color:seagreen;font-size:20pt;\">";
        echo "</form>";
        echo "</span >";
    }
    else header("Location: ./DodajTurniejHLP.php");
    
?>