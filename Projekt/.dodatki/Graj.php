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
        <title>GAME PAGE</title>
        <link rel="stylesheet" href="TurniejStyle.css">
        <?php
            // TODO skasować to 
            echo "<h1> Panel gry gracza  ".$_SESSION['LOGINDB'].' [<a href="UserPage.php">Wróć</a>]</h1>';
        ?>
        <style type='text/css'>
            td {
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
        <div class="granie" align = center>
        Gram w gierkę, ale fajnie!
        </div>

        <form action="
            <?php
                $is_ok = true;

                $find = "SELECT ID FROM KONTO WHERE NICK = '".$_POST['przeciwnik']."'";
                $find_id = oci_parse($conn, $find);
                oci_execute($find_id, OCI_NO_AUTO_COMMIT);
                $row = oci_fetch_array($find_id, OCI_BOTH);

                $find2 = "SELECT ID FROM KONTO WHERE NICK = '".$_POST['zwyc']."'";
                $find2_id = oci_parse($conn, $find2);
                oci_execute($find2_id, OCI_NO_AUTO_COMMIT);
                $row2 = oci_fetch_array($find2_id, OCI_BOTH);

                $enemy_id = $row['ID'];
                $loser = $enemy_id;
                $winner = $row2['ID'];
                
                if ($winner == $loser) {
                    $loser = $user_id;
                    $winner = $enemy_id;
                }

                if ($user_id == $enemy_id or ($winner != $user_id and $winner != $enemy_id)) {
                    $is_ok = false;
                }

                if ($is_ok) { 
                    $game_text = "INSERT INTO ROZGRYWKA (ID_ROZGRYWKI, GRACZ1, GRACZ2, ID_ZWYCIEZCY, GRA, PKT_PRZEWAGI, PRZEBIEG_PARTII) ".
                    "VALUES (0,$user_id,$enemy_id, $winner,'".$_POST['gra']."', 100,'".$_POST['przebieg']."')";
                    
                    $game_stmt = oci_parse($conn, $game_text);
                    oci_execute($game_stmt, OCI_NO_AUTO_COMMIT);

                    $check_if_i_exist = "SELECT * FROM PUNKTY WHERE  id_gracza = $user_id AND gra='".$_POST['gra']."'";
                    $i_exist_stmt = oci_parse($conn, $check_if_i_exist);
                    oci_execute($i_exist_stmt, OCI_NO_AUTO_COMMIT);

                    $row = oci_fetch_array($i_exist_stmt, OCI_BOTH);
                
                    // Jeśli nie istnieje jeszcze taki rekord to go dodajemy do bazy na punkty     
                    if (!$row) {
                        $add = "INSERT INTO PUNKTY VALUES ($user_id, '".$_POST['gra']."',0)";
                        $add_stmt = oci_parse($conn, $add); 
                        oci_execute($add_stmt, OCI_NO_AUTO_COMMIT);
                        oci_commit($conn);
                    }

                    $check_if_enemy_exist = "SELECT * FROM PUNKTY WHERE id_gracza = $enemy_id AND gra='".$_POST['gra']."'";
                    $enemy_exists_stmt = oci_parse($conn, $check_if_enemy_exist);
                    oci_execute($enemy_exists_stmt, OCI_NO_AUTO_COMMIT);
                    $row2 = oci_fetch_array($enemy_exists_stmt, OCI_BOTH);
                
                    // Jeśli nie istnieje jeszcze taki rekord to go dodajemy do bazy na punkty     
                    if (!$row2) {
                        $add = "INSERT INTO PUNKTY VALUES ($enemy_id, '".$_POST['gra']."',0)";
                        $add_stmt = oci_parse($conn, $add); 
                        oci_execute($add_stmt, OCI_NO_AUTO_COMMIT);
                        oci_commit($conn);
                    }

                    $get_last_id = "SELECT idrozgrywka_seq.currval ID FROM DUAL";
                    $last_id_stmt = oci_parse($conn,$get_last_id);
                    oci_execute($last_id_stmt);
                    $row = oci_fetch_array($last_id_stmt, OCI_BOTH);
                    $last_id = $row['ID'];

                    //$sql = 'BEGIN aktualizuj_pkt(:gra, :gracz1, :gracz2); END;';
                    $sql = 'BEGIN aktualizuj_pkt(:gra_id); END;';
                    
                    $stmt = oci_parse($conn,$sql);
                    oci_bind_by_name($stmt,':gra_id',$last_id,32);
                    oci_execute($stmt);
                    oci_commit($conn);
                }
                
            ?>
        " method = "post">
            <table align = center >
                <tr>
                    <td>Wpisz przeciwnika:</td>
                    <td><input type="text" name="przeciwnik"></td>
                </tr>
                <tr>
                    <td>Wpisz zwycięzcę:</td>
                    <td><input type="text" name="zwyc"><br></td>
                </tr>
                <tr>
                    <td>Wpisz grę:</td>
                    <td>
                        <?php
                            $games = "SELECT NAZWA FROM GRA";
                            $gm_stmt = oci_parse($conn, $games);
                            oci_execute($gm_stmt, OCI_NO_AUTO_COMMIT);

                            echo "<select id=\"gra\" name=\"gra\">";

                            while (($row = oci_fetch_array($gm_stmt, OCI_BOTH))) {
                                echo "<option value = ".$row['NAZWA'].">".$row['NAZWA']."</option>";
                            }

                            echo "</select>";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Wpisz przebieg partii:</td>
                    <td><input type="text" name="przebieg"></td>
                </tr>
                <tr><td align = center><input type="submit" value="Dodaj"></td></tr> 
            </table>

        </form>
        
    </body>
</html>