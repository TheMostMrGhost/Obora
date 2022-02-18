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
            $_SESSION['CONNECTION'] = $conn;
            header("Location: ./Main.php");
            exit;
        }
        //oci_close($conn);
    ?>
</html>