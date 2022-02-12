<html>
    <?PHP
        session_start();
        
        // Logowanie:
        $_SESSION['LOGIN'] = $_POST['LOGIN'];
        $_SESSION['PASSWORD'] = $_POST['PASSWORD'];
        $conn = oci_pconnect($_SESSION['LOGIN'], $_SESSION['PASSWORD'], "//labora.mimuw.edu.pl/LABS");

        if (!$conn) {
            echo "Nie udało się połączyć z bazą danych, spróbuj ponownie\n";
            $e = oci_error();
            echo $e['message'];
        }
        else {
            //header("Location: ./Main.html");
            //exit;

            $stm = oci_parse($conn,"SELECT * FROM USERS");
            oci_execute($stm, OCI_NO_AUTO_COMMIT);
            while (($row = oci_fetch_array($stm, OCI_BOTH))) {
                // Use UPPERCASE column names for the associative array indices and numbers for the ordinary array indices.
                echo "hahaha";
            }
            //$stmt = oci_parse($conn,"SELECT * FROM naukowiec WHERE promotor=1");
            //oci_execute($stmt, OCI_NO_AUTO_COMMIT);

            //while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
                //// Use UPPERCASE column names for the associative array indices and numbers for the ordinary array indices.
                //echo "<BR><A HREF=\"doktoranci.php?id=".$row['ID']."\">".$row[1]." ".$row['NAZWISKO']."<A><BR>\n";
            //}


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
            }





            //header("Location: ./Main.html");
            //exit;
        }
        //oci_close($conn);
    ?>
</html>