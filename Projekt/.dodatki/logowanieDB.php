<html>
    <?php
        session_start();        

        $_SESSION['LOGINDB'] = $_POST['LOGINDB'];
        $_SESSION['PASSWORDDB'] = $_POST['PASSWORDDB'];

        //$login = oci_parse($conn,"SELECT login from USERS where login=".$_SESSION['LOGINDB']."AND password=".$_SESSION['PASSWORDDB']);
        $stm = oci_parse($conn,"SELECT * from USERS");
        oci_execute($stm, OCI_NO_AUTO_COMMIT);
        while (($row = oci_fetch_array($stm, OCI_BOTH))) {
            // Use UPPERCASE column names for the associative array indices and numbers for the ordinary array indices.
            echo "hahaha";
        }


        if ($stm) {
            if (oci_num_rows($stm) > 0) {
                header("Location: ./UserPage.html");
                exit;
            }
            else {
                echo "Pasy: ".$_SESSION['LOGINDB'];
                echo $_SESSION['PASSWORDDB'];
                echo oci_num_rows($stm);
                echo "Nie udało się zalogować, spróbuj ponownie";
            }
        }
        else {
            echo "Nie";
            echo oci_error()['message'];
        }
    ?>
</html>