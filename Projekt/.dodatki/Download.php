 <?php //Generate text file on the fly

session_start();    
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=downloaded.txt");
echo key($_POST['to_down']);
?>