<?php
    session_start();    
    $conn = oci_pconnect($_SESSION['LOGIN'], $_SESSION['PASSWORD'], "//labora.mimuw.edu.pl/LABS");

    if (!$conn) {
        echo "Nie udało się połączyć z bazą danych, spróbuj ponownie\n";
        $e = oci_error();
        echo $e['message'];
    }

    $_SESSION['wins'][$_POST['changed']] = $_POST['winner']; 
// Musimy zaktualizować dwa rekordy
    if (intdiv($_POST['changed'], 2) * 2 == $_POST['changed']) {
        $other_to_upd = $_POST['changed'] + 1; 
    }
    else { 
        $other_to_upd = $_POST['changed']; 
    }
    $_SESSION['wins'][$_POST['changed']] = $_POST['winner']; 
    $_SESSION['wins'][$other_to_upd] = $_POST['winner']; 
    echo $_POST['winner'];
    echo $_POST['changed'];
    //header("Location: ./DodajTurniej.php");
?>
