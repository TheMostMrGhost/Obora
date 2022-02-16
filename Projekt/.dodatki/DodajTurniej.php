<?php
    function binlog(int $x) {
        $ii = -1;
        
        while ($x > 0) {
            $ii++;
            $x = intdiv($x, 2);
            //echo $x."<br>";
            //echo $ii."<br>";
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

    if (!isset($_SESSION['region'])) {
        $_SESSION['region'] = 'NULL';
    }

    $creation_possible = true;

    if (!isset($ranking_table)) {
        $ranking_table = "PUNKTY";
    }

    $row_count = "SELECT DISTINCT COUNT(ID_GRACZA) ILE 
    FROM $ranking_table 
    JOIN KONTO ON ID_GRACZA = ID
    WHERE GRA = '".$_POST['gra_rank']."'";

    if ($_SESSION['region'] != 'NULL') {
        $row_count .=" AND REGION = '".$_POST['region']."'";
    }

    $row_count_stmt = oci_parse($conn, $row_count);
    oci_execute($row_count_stmt, OCI_NO_AUTO_COMMIT);
    $row = oci_fetch_array($row_count_stmt, OCI_BOTH);

    $count = $row['ILE'];
    $_SESSION['ILE'] = $count;

    if ($count < $_POST['ile_graczy']) {
        $creation_possible = false;
    }

    
    //header("Location: ./Turniej.php");
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
        <p>Graj</p>
       
        <?php
            if ($creation_possible) {
                    // TODO
                    $date = date('YYYY-MM-DD');
                    $create_tornament = "INSERT INTO TURNIEJ VALUES (0,'".$_POST['nazwa']."','".$_POST['gra_rank']."',SYSDATE)";
                    $create_tornament_stmt = oci_parse($conn, $create_tornament);
                    oci_execute($create_tornament_stmt, OCI_NO_AUTO_COMMIT);
                    
                    $players = "SELECT ID_GRACZA
                        FROM $ranking_table
                        WHERE GRA = '".$_POST['gra_rank']."'"; 

                    if ($_SESSION['region'] != 'NULL') {
                        $players .=" AND REGION = '".$_POST['region']."'";
                    }

                    $players .= " ORDER BY LICZBA_PUNKTOW DESC FETCH FIRST ".$_POST['ile_graczy']." ROWS ONLY";
                        
                    $find_players = oci_parse($conn, $players);

                    $last_added_row = "SELECT DISTINCT FIRST_VALUE(ID_TURNIEJU) OVER (ORDER BY ID_TURNIEJU DESC) ID FROM TURNIEJ";
                    $last_added_row_stmt = oci_parse($conn, $last_added_row);
                    oci_execute($last_added_row_stmt, OCI_NO_AUTO_COMMIT);

                    $temp = oci_fetch_array($last_added_row_stmt, OCI_BOTH);
                    $turniej_id = $temp['ID'];

                    oci_execute($find_players, OCI_NO_AUTO_COMMIT);

                    $ile = 2;
                    // $ile = $_POST['ile'];  <- to jest na potencjalne rozszerzenie drabinki do większej liczby graczy

                    for ($ii = 0; $ii < $_POST['ile_graczy'] / $ile; $ii++) { 
                        for ($jj = 0; $jj < $ile; $jj++) { 
                            $row = oci_fetch_array($find_players, OCI_BOTH);
                            $to_ins = "INSERT INTO UCZESTNIK_TURNIEJU VALUES (".$row['ID_GRACZA'].",$turniej_id, 0)";
                            $ins_stmt = oci_parse($conn, $to_ins);
                            oci_execute($ins_stmt, OCI_NO_AUTO_COMMIT);
                            //echo $row['ID_GRACZA']."<br>";
                        }
                    }

                    oci_commit($conn);
                }
                else {
                    $_SESSION['error'] = "Nie można zrobić drużyny!";
                }

                // Prezentacja drabinki

                // Tworzymy tabelę zwycięzców:
                if ($creation_possible) {
                    if (!isset($_SESSION['wins'])) {
                        $_SESSION['wins'] = array();
                        
                        for ($ii=0; $ii < 2 * $_POST['ile_graczy']; $ii++) { 
                            $_SESSION['wins'][$ii] = -1;
                        }
                    }


                echo "<table width = 100%>";
                echo "<tr align = center height = 150px>";

                for ($ii=0; $ii < $_POST['ile_graczy']; $ii++) { 
                    echo "<td >"; 
                    $_SESSION[2 * $_POST['ile_graczy'] - 1 - $ii] = 2 * $_POST['ile_graczy'] - 1 - $ii;
                    echo $ii;
                    //echo 2 * $_POST['ile_graczy'] - 1 - $ii;
                    echo "</td>";
                }

                echo "</tr>";


                for ($ii=binlog($_POST['ile_graczy']) - 1; $ii >= 0; $ii--) { 

                    echo "<tr align = center height = 150px>";
                    //echo power($ii + 3);
                    for ($jj=0; $jj < power($ii); $jj++) { 
                        $_POST['changed'] = power($ii) + $jj;
                        echo "<td colspan = ".($_POST['ile_graczy'] / power($ii)).">"; 
                        echo "|".(power($ii) + $jj)."|";
                        // Chcemy, żeby w obu komórkach tabeli było to samo
                        
                    //echo "<form action=\"./DodajTurniejHLP.php\" method = \"post\">";
                        //echo "<input type=\"hidden\" name=\"winner\" value=17>";
                        //echo "<input type=\"hidden\" name=\"changed\" value=36>";
                        //echo "<input type=\"submit\" name=\"submit\" value=1>";
                    //echo "</form>";

                        //echo "<input type=\"hidden\" name=\"winner\" value=25>";
                        //echo "<input type=\"hidden\" name=\"changed\" value=44>";
                        //echo "<input type=\"submit\" name=\"submit\" value=2>";

                    echo "<form action=\"./DodajTurniejHLP.php\" method = \"post\">";
                        echo "<input type=\"hidden\" name=\"winner\" value=".$_SESSION['wins'][2 * (power($ii) + $jj)].">";
                        echo "<input type=\"hidden\" name=\"changed\" value=".(power($ii) + $jj).">";
                        echo "<input type=\"submit\" name=\"submit\" value=1>";
                    echo "</form>";

                    echo "<form action=\"./DodajTurniejHLP.php\" method = \"post\">";
                        echo "<input type=\"hidden\" name=\"winner\" value=".$_SESSION['wins'][2 * (power($ii) + $jj) +1].">";
                        echo "<input type=\"hidden\" name=\"changed\" value=".(power($ii) + $jj).">";
                        echo "<input type=\"submit\"  name=\"submit\" value=5>";
                    echo "</form>";

                        if ($_SESSION['wins'][2* (power($ii) + $jj)] == -1) {
                            echo "N";
                        }
                        else {
                            echo $_SESSION['wins'][2 * (power($ii) + $jj)];
                        }

                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</form>";
                echo "</table>";









            }
            ?>
    </body>
</html>