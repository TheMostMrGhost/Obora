<?php
    session_start();
    $conn = oci_pconnect($_SESSION['LOGIN'], $_SESSION['PASSWORD'], "//labora.mimuw.edu.pl/LABS");

    if (!$conn) {
        echo "Nie udało się połączyć z bazą danych, spróbuj ponownie\n";
        $e = oci_error();
        echo $e['message'];
    }
?>
<html>
    <?php
        $new_log = $_POST['Log'];
        $new_pass = $_POST['Pass'];
        $new_pass2 = $_POST['Pass2'];
        $region = $_POST['Region'];
        $email = $_POST['Email'];
        $bank = 'NULL';
        if ($_POST['bank'] != NULL) {
            $bank = $_POST['bank'];
        }
        $typ = $_POST['konto'];

        // Sprawddzenie czy taki login nie istnieje pryzpadkiem w bazie:
        $check_dupl = "SELECT * FROM KONTO WHERE NICK = '$new_log'";
        $stm_check = oci_parse($conn, "SELECT * FROM KONTO WHERE NICK = '$new_log'");
        oci_execute($stm_check, OCI_NO_AUTO_COMMIT);
        oci_fetch_array($stm_check, OCI_BOTH);

        $is_ok = true;

        // Sprawdzenie duplikatów
        $check_dupl = "SELECT * FROM KONTO WHERE NICK = '$new_log'";
        $stm_check = oci_parse($conn, "SELECT * FROM KONTO WHERE NICK = '$new_log'");
        oci_execute($stm_check, OCI_NO_AUTO_COMMIT);
        oci_fetch_array($stm_check, OCI_BOTH);

        if (oci_num_rows($stm_check) > 0) {
            // Sprawddzenie czy taki login nie istnieje pryzpadkiem w bazie:
            echo "Taki login już istnieje, spróbuj ponownie!";
            $is_ok = false;
        }

        if ($new_pass != $new_pass2) {
            echo "Hasła nie są takie same!";
            $is_ok = false;
        }

        if ($typ == $bank && $bank == NULL) {
            echo "Podanie numeru konta bankowego dla konta profesjonalnego jest wymagane!";
            $is_ok = false;
        }

        if ($is_ok) {
            $test = "INSERT INTO KONTO (id, region, nick, haslo, email, rodzaj_konta, nr_konta_bankowego) VALUES (1,'$region','$new_log', '$new_pass', '$email','$typ', $bank)";
            $ast = oci_parse($conn, $test);
            oci_execute($ast);
            oci_commit($conn);

            $stm = oci_parse($conn,"SELECT * FROM KONTO WHERE nick='$new_log' AND haslo=$new_pass");
            $res = oci_execute($stm);
            $row = oci_fetch_array($stm, OCI_BOTH);

            if (oci_num_rows($stm) > 0) {
                $_SESSION['LOGINDB'] = $new_log;
                $_SESSION['USER_ID'] = $row['ID'];
                header("Location: UserPage.php");
                exit;
            }
            else {
                echo "Rejestracja nie powiodła się, spróbuj ponownie <br>";
            }
        }
    ?>
</html>