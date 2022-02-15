<?php
    session_start();    
    $username = $_SESSION['LOGINDB'];
    // TODO wyciągnąć ID
    $user_id = $_SESSION['USER_ID'];

    $conn = oci_pconnect($_SESSION['LOGIN'], $_SESSION['PASSWORD'], "//labora.mimuw.edu.pl/LABS");

    if (!$conn) {
        echo "Nie udało się połączyć z bazą danych, spróbuj ponownie\n";
        $e = oci_error();
        echo $e['message'];
    }

    if (!isset($_SESSION['rank_game'])) {
        $_SESSION['rank_game'] = 'SZACHY';
    }

    if (!isset($_SESSION['region'])) {
        $_SESSION['region'] = 'NULL';
    }
//    $rank_game = 'SZACHY';
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8">
    <head>
        <title>UserPage</title>

        <link rel="stylesheet" href="TurniejStyle.css" type="text/css">
        <?php
            echo "<h1 > Turnieje".' [<a href="Main.html">Wróć</a>]</h1>';
        ?>
        
    </head>
    <body>
        <p align = center style="font-size: 30pt">Witaj na stronie głównej turniejów! <br> Niech rozpocznie się rywalizacja!</p>

        <table width="1800" align = "center">
            <tr><td></td></tr>
        </table>

        
    </body>
</html>