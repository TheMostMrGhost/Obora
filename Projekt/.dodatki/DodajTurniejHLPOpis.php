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
        echo "Wpisz opis";
        echo "<form action=\"./DodajTurniejHLP.php\" method = \"post\">";
        echo "<input type=\"text\" name=\"description\" >";
        echo "<input type=\"submit\" name=\"submit\" value= 'Dodaj opis' style = \"color:black;border:0px #000 solid;background-color:seagreen;font-size:30pt;\">";
        echo "</form>";
    }
    else header("Location: ./DodajTurniejHLP.php");
    //if ($_SESSION['wins'][0] != -1 && !isset($_SESSION['comm'])) {// 
        //$_SESSION['comm'] = 1;

        //for ($ii = 1; $ii < 2 * $_SESSION['ile_graczy']; $ii++) { 
            //$ins = $_SESSION['to_ins'][$ii];
            //echo $ins;
            
            //$insert = oci_parse($conn, $ins);
            //oci_execute($insert);
        //}
        //oci_commit($conn);
    //}
?>
