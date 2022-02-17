<?php
    session_start();    
    $conn = oci_pconnect($_SESSION['LOGIN'], $_SESSION['PASSWORD'], "//labora.mimuw.edu.pl/LABS");

    if (!$conn) {
        echo "Nie udało się połączyć z bazą danych, spróbuj ponownie\n";
        $e = oci_error();
        echo $e['message'];
    }

    $loser = $_SESSION['wins'][$_SESSION['changed'] * 2 + 1];

    if ($loser == $_SESSION['winner']) {
        $loser = $_SESSION['wins'][$_SESSION['changed'] * 2];
    }
    
    if ($_SESSION['wins'][intdiv($_SESSION['changed'], 2)] == - 1 && $loser != -1) {
        $_SESSION['wins'][$_SESSION['changed']] = $_SESSION['winner']; 
        $_SESSION['to_ins'][$_SESSION['changed']] = "INSERT INTO HISTORIA_TURNIEJU VALUES (".$_SESSION['turniej_id'].",".
        $_SESSION['winner'].",".$loser.", NULL, NULL,".$_SESSION['winner'].","."'".$_POST['description']."'".")";
        //echo $_SESSION['winner'];
        //echo $_SESSION['to_ins'][$_SESSION['changed']];
        //echo $_SESSION['changed'];
    }

    if ($_SESSION['changed'] == 0) {
        $_SESSION['wins'][0] = $_SESSION['winner']; 
    }

    if ($_SESSION['wins'][0] != -1 && !isset($_SESSION['comm'])) {// 
        $_SESSION['comm'] = 1;

        for ($ii = 1; $ii < 2 * $_SESSION['ile_graczy']; $ii++) { 
            $ins = $_SESSION['to_ins'][$ii];
            echo $ins;
            
            $insert = oci_parse($conn, $ins);
            oci_execute($insert);
        }
        oci_commit($conn);
    }
    header("Location: ./DodajTurniej.php");
?>
