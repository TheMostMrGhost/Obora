<?php
    session_start();
?>
<html>
    <?php
        $conn = oci_pconnect($_SESSION['LOGIN'], $_SESSION['PASSWORD'], "//labora.mimuw.edu.pl/LABS");

        if (!$conn) {
            echo "Nie udało się połączyć z bazą danych, spróbuj ponownie\n";
            $e = oci_error();
            echo $e['message'];
        }

        $_SESSION['Log'] = $_POST['Log'];
        $_SESSION['Pass'] = $_POST['Pass'];
        $_SESSION['Pass2'] = $_POST['Pass2'];
        echo $_SESSION['Pass2'];
        echo $_SESSION['Pass'];
        echo $_SESSION['Log'];

        $test = "INSERT INTO USERS (login, password) VALUES ('adam', 'adam')";
        //$ast = oci_parse($conn,"INSERT INTO USERS (login, password) VALUES ('nic', 'nic')");
        $ast = oci_parse($conn, $test);
        oci_execute($ast);
        oci_commit($conn);

        $stm = oci_parse($conn,"SELECT * FROM USERS");
        $res = oci_execute($stm);
        oci_fetch_array($stm, OCI_BOTH);

        if (!$conn) {
            echo "PROBLEM";
        }

        if (oci_num_rows($stm) > 0) {
            echo "TAK";
        }
        else {
        echo "NIE";
        }
        //$create_acc = oci_parse($conn, "INSERT INTO USERS VALUES (".$_SESSION['Log'].','.$_SESSION['Pass'].")");
        //oci_execute($create_acc);
        //oci_commit($conn);
    ?>
</html>