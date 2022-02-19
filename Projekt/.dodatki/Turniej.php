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

    if (isset($_SESSION['wins'])) {
        unset($_SESSION['wins']);
    }

    if (isset($_SESSION['ile_graczy']))
        unset($_SESSION['ile_graczy']);

    if (isset($_SESSION['comm']))
        unset($_SESSION['comm']);


    if (isset($_SESSION['turniej_id']))
        unset($_SESSION['turniej_id']);
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8">
    <head>
        <title>UserPage</title>

        <link rel="stylesheet" href="TurniejStyle.css" type="text/css">
        <?php
            echo "<h1 > Turnieje".' [<a href="Main.php">Wróć</a>]</h1>';
        ?>
        <style>
            .error
            {
                color:red;
                margin-top: 10px;
                margin-bottom: 10px;
                font-size : 30pt;
            }
	    </style>
        
    </head>
    <body>
        <p align = center style="font-size: 30pt">Witaj na stronie głównej turniejów! <br> Niech rozpocznie się rywalizacja!</p>

        <table width="100%" align = "center">
            <tr><td align = center>
                <table width = 1000>
                    <form action="DodajTurniej.php" method ="post">
                    <tr><td align = center colspan = 2>Dodaj turniej:</td></tr>
                    <tr><td>Wpisz nazwę:</td><td align = right><input type="text" name = "nazwa"></td>
                    <tr><td>Wybierz grę:</td>
                    <td align = right>
                        <?php
                            $poss_games = "SELECT NAZWA FROM GRA";
                            $avl_games = oci_parse($conn, $poss_games);
                            oci_execute($avl_games, OCI_NO_AUTO_COMMIT);

                            echo "<select id=\"gra_rank\" name=\"gra_rank\">";

                            while (($row = oci_fetch_array($avl_games, OCI_BOTH))) {
                                echo "<option value=".$row['NAZWA'];

                                if ($_SESSION['rank_game'] == $row['NAZWA']) {
                                    echo " selected = \"selected\"";
                                }

                                echo ">".$row['NAZWA']."</option>";
                            }

                            echo "</select>";
                        ?>
                    </td>
                    </tr>
                    <tr><td>Wybierz liczbę graczy:</td><td align = right>
                        <select name="ile_graczy" id="ile_graczy">
                            <option value=4 selected = selected>4</option>
                            <option value=8>8</option>
                            <option value=16>16</option>
                        </select>
                    </td></tr>
                    <tr>
                        <td>Wybierz region:</td>
                        <td align = right>
                            <?php
                                $reg = "SELECT DISTINCT REGION from konto";
                                $reg_filter = oci_parse($conn, $reg);
                                oci_execute($reg_filter, OCI_NO_AUTO_COMMIT);

                                echo "<select id=\"region\" name=\"region\">";

                                echo "<option value=NULL";

                                if ($_SESSION['region'] == $row['REGION']) {
                                    echo " selected = \"selected\"";
                                }

                                echo ">"."BRAK"."</option>";

                                while (($row = oci_fetch_array($reg_filter, OCI_BOTH))) {
                                    echo "<option value='".$row['REGION']."'";

                                    if ($_SESSION['region'] == $row['REGION']) {
                                    echo " selected = \"selected\"";
                                    }

                                    echo ">".$row['REGION']."</option>";
                                }

                                echo "</select>";
                            ?>
                        </td>
                    </tr>
                    
                    <tr ><td align = center colspan = 2><input type="submit" value="Zatwierdź"></td></tr>
                </form></table>
            </td></tr>
        </table>
    <?php
        if(isset($_SESSION['error']) && !isset($_SESSION['is_created'])) {
            echo '<div class="error">Zbyt mało graczy odpowiadającym wymaganim,<br> utworzenie turnieju jest niemożliwe!</div>';
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['is_created']))
            unset($_SESSION['is_created']);
    ?>
        
    </body>
</html>