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

        $ile = $_POST['ile'];

        for ($ii = 0; $ii < $_POST['ile_graczy'] / $ile; $ii++) { 
            for ($jj = 0; $jj < $ile; $jj++) { 
                $row = oci_fetch_array($find_players, OCI_BOTH);
                $to_ins = "INSERT INTO UCZESTNIK_TURNIEJU VALUES (".$row['ID_GRACZA'].",$turniej_id, 0)";
                $ins_stmt = oci_parse($conn, $to_ins);
                oci_execute($ins_stmt, OCI_NO_AUTO_COMMIT);
                echo $row['ID_GRACZA']."<br>";
            }
            echo "<br><br>";
        }

        oci_commit($conn);
    }
    else {
        $_SESSION['error'] = "Nie można zrobić drużyny!";
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
            echo "<h1 > Turniej ".' [<a href="Main.html">Wróć</a>]</h1>';
        ?>
       
    </head>
    <body>
       
    </body>
</html>