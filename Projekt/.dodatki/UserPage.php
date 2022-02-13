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
        <link rel="stylesheet" href="Style.css">
        <?php
            echo "<h1> Witaj ".$_SESSION['LOGINDB'].'! [<a href="wyloguj.php">Wyloguj</a>]</h1>';
        ?>
        <style type='text/css'>
            td {
                /* border-bottom: 2px solid #CDC1A7; */
                padding: 1px;
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
        </style>
    </head>
    <body>
        <p>Heheh czy to działa </p>

        
        <table width="1800" align = "center">
            <th>Graj</th>
            <th>
                Znajomi
                <form action="
                    <?php

                        // TODO wyszukiwanie id
                        
                        $add_text = "INSERT INTO ZNAJOMI VALUES (".$user_id.",".$_POST['Friend_id'].")"; 
                        $add_stmt = oci_parse($conn, $add_text);
                        oci_execute($add_stmt, OCI_NO_AUTO_COMMIT);
                        oci_commit($conn);
                    ?>
                " method = "post">
                <input type="text" name = "Friend_id">
                <input type="submit" value = "Dodaj">
            </form>

            <form action="
                <?php
                    $find = "SELECT ID FROM KONTO WHERE NICK = '".$_POST['friend_to_del']."'";
                    $find_id = oci_parse($conn, $find);
                    oci_execute($find_id, OCI_NO_AUTO_COMMIT);
                    $row = oci_fetch_array($find_id, OCI_BOTH);

                    if (oci_num_rows($find_id) > 0) {

                        $check = "SELECT * FROM ZNAJOMI WHERE (GRACZ1 =".$user_id." AND gracz2 = "
                        .$row['ID'].") OR (GRACZ2 = "
                        .$user_id." AND GRACZ1 = ".$row['ID'].")";

                        $check_stmt = oci_parse($conn, $check);
                        oci_execute($check_stmt, OCI_NO_AUTO_COMMIT);
                        oci_fetch_array($check_stmt, OCI_BOTH);

                        if (oci_num_rows($check_stmt) > 0) {

                            $del_f = "DELETE FROM ZNAJOMI WHERE (GRACZ1 =".$user_id." AND gracz2 = "
                            .$row['ID'].") OR (GRACZ2 = "
                            .$user_id." AND GRACZ1 = ".$row['ID'].")";

                            $del_stmt = oci_parse($conn, $del_f);
                            oci_execute($del_stmt, OCI_NO_AUTO_COMMIT);
                            oci_commit($conn);
                            //echo $_POST['friend_to_del'];
                        }
                    }
                ?>" method = 'post'>
                <input type="text" name = "friend_to_del">
                <input type="submit" value = "Usuń">
            </form>

            </th>
            <th>
                Najlepsi gracze
                <table>
                    <tr>
                        <td>
                        <form action=
                            "<?php 
                                $_SESSION['rank_game'] = $_POST['gra_rank'];
                                $_SESSION['region'] = $_POST['region'];
                                echo $_SERVER['PHP_SELF']; 
                            ?>"  
                            method="post">

                            <?php
                                $poss_games = "SELECT DISTINCT GRA FROM PUNKTY";
                                $avl_games = oci_parse($conn, $poss_games);
                                oci_execute($avl_games, OCI_NO_AUTO_COMMIT);

                                echo "<select id=\"gra_rank\" name=\"gra_rank\">";

                                while (($row = oci_fetch_array($avl_games, OCI_BOTH))) {
                                    echo "<option value=".$row['GRA'];

                                    if ($_SESSION['rank_game'] == $row['GRA']) {
                                        echo " selected = \"selected\"";
                                    }

                                    echo ">".$row['GRA']."</option>";
                                }

                                echo "</select>";

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
                                    echo "<option value=".$row['REGION'];

                                    if ($_SESSION['region'] == $row['REGION']) {
                                        echo " selected = \"selected\"";
                                    }

                                    echo ">".$row['REGION']."</option>";
                                }

                                echo "</select>";
                            ?>
                            <input type="submit" name="Zatwierdz" value="Zatwierdź"> 
                        </form>
                        </td>

                    </tr>
                </table> 
            </th>

           
            <tr>
                <td 
                    align="center" 
                    width="500" 
                    valign = "top" 
                    style=
                        "font-size: 25pt;
                        color: white; 
                        "
                    
                >
                    <?php
                        $games = "SELECT nazwa FROM GRA";
                    
                        $gm_stmt = oci_parse($conn, $games);
                        oci_execute($gm_stmt, OCI_NO_AUTO_COMMIT);

                        echo $_SESSION['temp'];
                        // Wyświetlanie listy znajomych
                        while (($row = oci_fetch_array($gm_stmt, OCI_BOTH))) {
                            echo $row['NAZWA']."<br><br>";
                        }
                    ?>
                </td>
                <td 
                    width="500" 
                    valign = "top" 
                    style=
                        "font-size: 25pt;
                        color: red; 
                        "
                    align = center
                    >

                    <?php
                        $friends_list = "SELECT * FROM 
                        (SELECT nick FROM ZNAJOMI JOIN KONTO ON gracz1 = id where gracz2 = $user_id) UNION 
                        (SELECT nick FROM ZNAJOMI JOIN KONTO ON gracz2 = id where gracz1 = $user_id)";
                    
                        $fl_stm = oci_parse($conn, $friends_list);
                        oci_execute($fl_stm, OCI_NO_AUTO_COMMIT);

                        // Wyświetlanie listy znajomych
                        while (($row = oci_fetch_array($fl_stm, OCI_BOTH))) {
                            echo $row['NICK'];
                            //echo "  "."<form action = ";


                            //echo ">";
                            //echo "<input type=\"submit\" value = \"Usuń\">";
                            //echo "</form>";
                            echo "<br><br>";
                        }
                    ?>
                    
                   <!-- <form action = "<?php
                        //$del_f = "DELETE FROM ZNAJOMI WHERE GRACZ1 =".$user_id." AND gracz2 = ".$_POST['friend_to_del'];
                        //$del_f .= " OR GRACZ2 = ".$user_id." AND GRACZ1 = ".$_POST['friend_to_del'];
                        //$del_stmt = oci_parse($conn, $del_f);
                        //oci_execute($del_stmt, OCI_NO_AUTO_COMMIT);
                        //oci_commit($conn);
                    //?>"> -->
                    </form>

                </td>
                <td
                    valign = "top" 
                    style=
                        "font-size: 15pt;
                        color: white; 
                        "
                >
                    <table widht = 800 align = center>

                    <?php
                        $rank_game = $_SESSION['rank_game'];
                        $rank = "SELECT NICK, LICZBA_PUNKTOW FROM KONTO JOIN PUNKTY ON id = id_gracza 
                        WHERE GRA = '$rank_game'" ;

                        if ($_SESSION['region'] != 'NULL') {
                            $rank .= " AND REGION = "."'".$_SESSION['region']."'"." ";
                        }
                        
                        $rank .= "ORDER BY LICZBA_PUNKTOW DESC";


                        $rank_stmt = oci_parse($conn, $rank);
                        oci_execute($rank_stmt, OCI_NO_AUTO_COMMIT);

                        // Wyświetlanie listy znajomych
                        while (($row = oci_fetch_array($rank_stmt, OCI_BOTH))) {
                            echo "<tr><td align = left width = 400>".$row['NICK']."</td><td align = right width = 400>".$row['LICZBA_PUNKTOW']."</td></tr>";
                        }
                    ?>
                    </table>
                </td>
            </tr>
        </table>
        
    </body>
</html>