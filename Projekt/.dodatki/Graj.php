<?php
    session_start();    
    $username = $_SESSION['LOGINDB'];
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
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8">
    <head>
        <title>UserPage</title>
        <link rel="stylesheet" href="Style.css">
        <?php
            // TODO skasować to 
            echo "<h1> Witaj ".$_SESSION['LOGINDB'].'! [<a href="wyloguj.php">Wyloguj</a>]</h1>';
        ?>
        <style type='text/css'>
            td {
                /* border-bottom: 2px solid #CDC1A7; */
                padding: 1px;
                font-size: 25pt;
                color: white; 
                    
            }
            th {
                font-family: "Trebuchet MS", Arial, Verdana;
                font-size: 44px;
                padding: 5px;
                border-bottom-width: 1px;
                border-bottom-style: solid;
                border-bottom-color: #CDC1A7;
                background-color: #CDC1A7;
                color: #993300;
            }
            p {
                font-size: 70px;
                align : center;
            }
            input {
                background-color: #4CAF50; /* Green */
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
            }
        </style>
    </head>
    <body>
        <p>Gram w gierkę, ale fajnie</p>

        <form action="
            <?php
                //$game_text = "INSERT INTO ROZGRYWKI (ID_ROZGRYWKI, GRACZ1, GRACZ2, GRACZ3, GRACZ4, ID_ZWYCIEZCY, GRA, PRZEBIEG_PARTII) VALUES (9, 5, 8, NULL, NULL, 5, 'SZACHY', 'NIC')";
                $game_text = "INSERT INTO ROZGRYWKI (ID_ROZGRYWKI, GRACZ1, GRACZ2, ID_ZWYCIEZCY, GRA, PRZEBIEG_PARTII) ".
                "VALUES (".$_POST['id'].",$user_id,".$_POST['przeciwnik'].",".$_POST['zwyc'].",'".$_POST['gra']."','".$_POST['przebieg']."')";
                $game_stmt = oci_parse($conn, $game_text);
                //echo $_POST['przebieg'];
                oci_execute($game_stmt, OCI_NO_AUTO_COMMIT);
                oci_commit($conn);
            ?>
        " method = "post">
            <!-- FIXME TODO usunąć id-->
            <table align = center >
                <tr>
                    <td>
                        Wpisz idgry:
                    </td>
                    <td>
                        <input type="number" name="id">
                    </td>
                </tr>
                <tr>
                    <td>
                        Wpisz przeciwnika:
                    </td>
                    <td>
                        <input type="number" name="przeciwnik">
                    </td>
                </tr>
                <tr>
                    <td>
                        Wpisz zwycięzcę:
                    </td>
                    <td>
                        <input type="number" name="zwyc"><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        Wpisz grę:
                    </td>
                    <td>
                        <input type="text" name="gra">
                    </td>
                </tr>
                <tr>
                    <td>
                        Wpisz przebieg partii:
                    </td>
                    <td>
                        <input type="text" name="przebieg">
                    </td>
                </tr>
                 
                <tr>
                    <td>
                        <input type="submit" value="Dodaj">
                    </td>
                </tr> 
            </table>

        </form>
        
    </body>
</html>