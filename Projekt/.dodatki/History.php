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
        <style type='text/css'>
            td {
                /* border-bottom: 2px solid #CDC1A7; */
                padding: 1px;
                font-size: 50px;
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
            h1 {
                font-size: 80px;
            }
            
            h2 {
                font-size: 50px;
            }
        </style>
        <title>History</title>
        <link rel="stylesheet" href="Style.css">
        <?php
            echo "<h1 > Historia gier gracza ".$_SESSION['LOGINDB'].' [<a href="UserPage.php">Wróć</a>]</h1>';
        ?>
        
    </head>
    <body>
        <!-- <p align = center>Panel główny gracza <?php echo $_SESSION['LOGINDB']; ?></p> -->
        <table width = 100%>
        <form action="Download.php" method = "post">
            <!-- <?php
                for ($i = 0; $i < 100 ; $i++) {
                    echo '<input type="submit" name="clicked['.$i.']" value="clicked" />';
                }
            ?> -->
            <tr><td>Gra przeciw:</td> <td>Zwycięzca:</td><td></td></tr>
            <?php 
                $wrog = "SELECT ID_ROZGRYWKI, NICK, PRZEBIEG_PARTII, ID_ZWYCIEZCY 
                FROM ( 
                    (SELECT ID_ROZGRYWKI, GRACZ1 AS WROG, PRZEBIEG_PARTII, ID_ZWYCIEZCY 
                    FROM ROZGRYWKA WHERE GRACZ2 = $user_id) 
                    UNION ALL 
                    (SELECT ID_ROZGRYWKI, GRACZ2 AS WROG, PRZEBIEG_PARTII, ID_ZWYCIEZCY 
                    FROM ROZGRYWKA WHERE GRACZ1 = $user_id)
                    ) 
                JOIN KONTO ON WROG = ID
                ORDER BY ID_ROZGRYWKI";
                $wrog_stmt = oci_parse($conn, $wrog);
                oci_execute($wrog_stmt, OCI_NO_AUTO_COMMIT);

                while ( ($row = oci_fetch_array($wrog_stmt)) ) {
                    echo "<tr><td>";
                    echo $row['NICK'];
                    echo "</td>";

                    if ($row['ID_ZWYCIEZCY'] == $user_id) {
                        echo "<td style = \"color : limegreen\">$username</td>";
                    }
                    else {
                        echo "<td style = \"color : red\">".$row['NICK']."</td>";
                    }

                    echo "<td align = center>";
                    echo '<input type="submit" name="to_down['.$row['PRZEBIEG_PARTII'].']" value="Pobierz" />';
                    echo "</td></tr>";
                }
                
            ?> 
        </form>
    </table>
        
    </body>
</html>