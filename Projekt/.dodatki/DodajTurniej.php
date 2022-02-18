<?php
    function binlog(int $x) {
        $ii = -1;
        
        while ($x > 0) {
            $ii++;
            $x = intdiv($x, 2);
        }

        return $ii;
    }

    function power($x) {
        $ii = 0;
        $res = 1;

        while ($ii < $x) {
            $ii++;
            $res = 2 * $res ;
        }

        return $res;
    }
?>
<?php
    session_start();    
    $conn = oci_pconnect($_SESSION['LOGIN'], $_SESSION['PASSWORD'], "//labora.mimuw.edu.pl/LABS");

    if (!$conn) {
        echo "Nie udało się połączyć z bazą danych, spróbuj ponownie\n";
        $e = oci_error();
        echo $e['message'];
    }

    if (!isset($_SESSION['rank_game'])) {
        $_SESSION['rank_game'] = 'SZACHY';
    }

    $creation_possible = true;

    if (!isset($ranking_table)) {
        $ranking_table = "PUNKTY";
    }

    if (!isset($_SESSION['ile_graczy']))
        $_SESSION['ile_graczy'] = $_POST['ile_graczy'];

    $_SESSION['gra_rank'] = $_POST['gra_rank'];

    if (!isset($_SESSION['region'])) 
        $_SESSION['region'] = $_POST['region'];


    $row_count = "SELECT DISTINCT COUNT(ID_GRACZA) ILE 
    FROM $ranking_table 
    JOIN KONTO ON ID_GRACZA = ID
    WHERE GRA = '".$_SESSION['gra_rank']."'";

    if ($_POST['region'] != 'NULL') {
        $row_count .=" AND REGION = '".$_POST['region']."'";
    }

    $row_count_stmt = oci_parse($conn, $row_count);
    oci_execute($row_count_stmt, OCI_NO_AUTO_COMMIT);
    $row = oci_fetch_array($row_count_stmt, OCI_BOTH);

    $count = $row['ILE'];
    $_SESSION['ILE'] = $count;

    if ($count < $_SESSION['ile_graczy']) {
        $creation_possible = false;
    }

    if (isset($_SESSION['error']) && !isset($_SESSION['is_created'])) {
        header("Location: ./Turniej.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8">
    <head>
        <title>Turniej</title>

        <link rel="stylesheet" href="TurniejStyle.css" type="text/css">
        <?php
            // TODO dodać nazwę
            echo "<h1 > Turniej ".' [<a href="Turniej.php">Wróć</a>]</h1>';
        ?>
       
    </head>
    <body>
        <!--<p>Graj</p>-->
       
        <?php

            if ($creation_possible && !isset($_SESSION['is_created'])) {
                    $_SESSION['is_created'] = true;
                    // TODO
                    $date = date('YYYY-MM-DD');
                    $create_tornament = "INSERT INTO TURNIEJ VALUES (0,'".$_POST['nazwa']."','".$_SESSION['gra_rank']."',SYSDATE)";
                    $create_tornament_stmt = oci_parse($conn, $create_tornament);
                    oci_execute($create_tornament_stmt, OCI_NO_AUTO_COMMIT);

                    $players = "SELECT ID_GRACZA, NICK
                        FROM $ranking_table 
                        JOIN KONTO ON ID_GRACZA = ID
                        WHERE GRA = '".$_SESSION['gra_rank']."'"; 

                    if ($_POST['region'] != 'NULL') {
                        $players .=" AND REGION = '".$_POST['region']."'";
                    }
                    

                    $players .= " ORDER BY LICZBA_PUNKTOW DESC FETCH FIRST ".$_SESSION['ile_graczy']." ROWS ONLY";
                        
                    $find_players = oci_parse($conn, $players);

                    $last_added_row = "SELECT IDTURNIEJ_SEQ.CURRVAL ID FROM DUAL";
                    $last_added_row_stmt = oci_parse($conn, $last_added_row);
                    oci_execute($last_added_row_stmt, OCI_NO_AUTO_COMMIT);

                    $temp = oci_fetch_array($last_added_row_stmt, OCI_BOTH);
                    $_SESSION['turniej_id'] = $temp['ID'];
                    oci_execute($find_players, OCI_NO_AUTO_COMMIT);

                    if (!isset($_SESSION['wins'])) {
                        $_SESSION['wins'] = array();
                        $_SESSION['to_ins'] = array();
                        
                        for ($ii=0; $ii < 2 * $_SESSION['ile_graczy']; $ii++) { 
                            $_SESSION['wins'][$ii] = -1;
                            $_SESSION['to_ins'][$ii] = "";
                        }
                    }

                    $ile = 2;
                    // $ile = $_SESSION['ile'];  <- to jest na potencjalne rozszerzenie drabinki do większej liczby graczy

                    $_SESSION['nick'] = array();

                    for ($ii = 0; $ii < $_SESSION['ile_graczy'] / $ile; $ii++) { 
                        for ($jj = 0; $jj < $ile; $jj++) { 
                            $row = oci_fetch_array($find_players, OCI_BOTH);

                            $to_ins = "INSERT INTO UCZESTNIK_TURNIEJU VALUES (".$row['ID_GRACZA'].",".$_SESSION['turniej_id'].", 0)";
                            $_SESSION['wins'][2 * $_SESSION['ile_graczy'] -  2 * $ii - $jj - 1] = $row['ID_GRACZA'];
                            $_SESSION['nick']["'".$row['ID_GRACZA']."'"] = $row['NICK'];

                            $ins_stmt = oci_parse($conn, $to_ins);
                            oci_execute($ins_stmt, OCI_NO_AUTO_COMMIT);
                            oci_commit($conn);
                        }
                    }

                    oci_commit($conn);
                }
                if (!$creation_possible && !isset($_SESSION['is_created'])){
                    $_SESSION['error'] = "Nie można zrobić drużyny!";
                }

                // Prezentacja drabinki
                // Tworzymy tabelę zwycięzców
                if ($creation_possible || isset($_SESSION['is_created'])) {

                echo "<table width = 100%>";
                echo "<tr align = center height = 150px>";

                for ($ii=0; $ii < $_SESSION['ile_graczy']; $ii++) { 
                    echo "<td >"; 
                    echo "<form action=\"./DodajTurniejHLPOpis.php\" method = \"post\">";
                    echo "<input type=\"hidden\" name=\"winner\" value=".$_SESSION['wins'][2 * $_SESSION['ile_graczy'] - $ii - 1].">";
                    echo "<input type=\"hidden\" name=\"changed\" value=".(intdiv($_SESSION['ile_graczy']  + $ii, 2)).">";
                    //echo "<input type=\"submit\" name=\"submit\" value=".$_SESSION['wins'][2 * $_SESSION['ile_graczy'] - $ii - 1].
                        //" style = \"color:black;border:0px #000 solid;background-color:seagreen;font-size:30pt;\">";
                    echo "<input type=\"submit\" name=\"submit\" value=".$_SESSION['nick']["'".($_SESSION['wins'][2 * $_SESSION['ile_graczy'] - $ii - 1])."'"].
                        " style = \"color:black;border:0px #000 solid;background-color:seagreen;font-size:30pt;\">";
                    echo "</form>";
                    echo "</td>";
                }
                echo "</tr>";


                for ($ii = binlog($_SESSION['ile_graczy']) - 1; $ii >= 0; $ii--) { 
                    echo "<tr align = center height = 150px>";

                    for ($jj=0; $jj < power($ii); $jj++) { 
                        echo "<td colspan = ".($_SESSION['ile_graczy'] / power($ii)).">"; 
                       
                            echo "<table><tr><td>";
                            $player_id = $_SESSION['wins'][(power($ii) + $jj)];
                            $nazwa = $_SESSION['nick']["'".$player_id."'"];
                            
                            if ($_SESSION['wins'][(power($ii) + $jj)] != -1 ) {
                                echo "<form action=\"./DodajTurniejHLPOpis.php\" method = \"post\">";
                                echo "<input type=\"hidden\" name=\"winner\" value=".$_SESSION['wins'][(power($ii) + $jj)].">";
                                echo "<input type=\"hidden\" name=\"changed\" value=".intdiv(power($ii) + $jj,2).">";

                                if (power($ii) + $jj == 1 && $_SESSION['wins'][(power($ii) + $jj)] != -1) {
                                    echo "<input type=\"submit\" name=\"submit\" value= ".$nazwa.
                                         " style = \"color:orange;border:0px #000 solid;background-color:seagreen;font-size:30pt;\">";
                                }
                                else {
                                    echo "<input type=\"submit\" name=\"submit\" value= ".$nazwa
                                        ." style = \"color:black;border:0px #000 solid;background-color:seagreen;font-size:30pt;\">";
                                }

                                echo "</form>";
                            }
                            
                            echo "</td>";
                            echo "</tr></table>";
                                             echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</form>";
                echo "</table>";
            
                if ($_SESSION['wins'][0] != -1 ) {
                    echo "<div align = center style = \"color:black;background-color:seagreen;font-size:30pt;\">";
                    echo "Turniej zakończony, naciśnij przycisk żeby wyjść";
                    echo "</div>";
                    echo "<form action=\"./Turniej.php\" method = \"post\" align = center>";
                    echo "<input type=\"submit\" name=\"submit\" value=\"Wróć\"
                         style = \"color:black;border:1px #000 solid;background-color:seagreen;font-size:20pt;\">";
                    echo "</form>";
                }
            }
            ?>
    </body>
</html>
