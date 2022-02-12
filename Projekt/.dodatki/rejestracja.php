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

        // Sprawddzenie czy taki login nie istnieje pryzpadkiem w bazie:
        $check_dupl = "SELECT * FROM USERS WHERE LOGIN = '$new_log'";
        $stm_check = oci_parse($conn, "SELECT * FROM USERS WHERE LOGIN = '$new_log'");
        oci_execute($stm_check, OCI_NO_AUTO_COMMIT);
        oci_fetch_array($stm_check, OCI_BOTH);

        $is_ok = true;
        //while (($row = oci_fetch_array($c, OCI_BOTH))) {
            //// Use UPPERCASE column names for the associative array indices and numbers for the ordinary array indices.
            //echo "hahaha";
        //}

        // Sprawdzenie duplikatów
        $check_dupl = "SELECT * FROM USERS WHERE LOGIN = '$new_log'";
        $stm_check = oci_parse($conn, "SELECT * FROM USERS WHERE LOGIN = '$new_log'");
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

        if ($is_ok) {
            $test = "INSERT INTO USERS (login, password) VALUES ('$new_log', '$new_pass')";
            //$test = "INSERT INTO USERS (login, password) VALUES ('adam', 'adam')";
            //$ast = oci_parse($conn,"INSERT INTO USERS (login, password) VALUES ('nic', 'nic')");
            $ast = oci_parse($conn, $test);
            oci_execute($ast);
            oci_commit($conn);

            $stm = oci_parse($conn,"SELECT * FROM USERS");
            $res = oci_execute($stm);
            oci_fetch_array($stm, OCI_BOTH);

            if (oci_num_rows($stm) > 0) {
                $_SESSION['LOGINDB'] = $new_log;
                header("Location: UserPage.php");

                exit;
                //echo "TAK";
            }
            else {
                echo "Rejestracja nie powiodła się, spróbuj ponownie <br>";
            }
        }
    ?>
</html>