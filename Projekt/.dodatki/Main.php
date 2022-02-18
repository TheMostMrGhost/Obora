<?php
    if (isset($_SESSION['is_created']))
        unset($_SESSION['is_created']);

    if (isset($_SESSION['error']))
        unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8">
<head>
        <title>Kurnki v. 32452345234</title>
        <link rel="stylesheet" href="Style.css">
        <h1>
            Witaj na kurniku ileśtam!
        </h1>
        <p>
            Witaj na kurniku ileśtam, wspaniałej stronie do gier na której stracisz mnóstwo czasu, który 
            <i> mógłbyś </i>poświęcić na coś innego.
        </p>
    </head>
    <body>
        <table width = 100% align="center"><tr>
            <td>
        Zaloguj się: 
        <form action="logowanieDB.php"  method="post" >
                Login: <input type="text" name="LOGINDB"> <br>
                Hasło: <input type="password" name="PASSWORDDB"> <br>
                <input type="submit" name="Zaloguj" value="Zaloguj"> 
        </form>
        </td>
        <td >
       <table width = 600 align="center">
       <form action="rejestracja.php" method="post">
            <tr><td colspan="2" align="center">Nie masz jeszcze konta? <br>
                Zarejestruj się!</td></tr>
            <tr><td>Rodzaj konta:</td>
            <td>
                <select name="konto" id="konto">
                    <option value="Zwykle">Zwykłe</option>
                    <option value="Profesjonalne">Profesjonalne</option>
                    <option value="Druzynowe">Drużynowe</option>
                </select>
            </td></tr>
            <tr><td>Region:</td><td> <input type="text" name="Region"> </td></tr>
            <tr><td>Email:</td><td> <input type="text" name="Email"> </td></tr>
            <tr><td> Login: </td><td><input type="text" name="Log">  </td></tr>
            <tr><td> Hasło: </td><td><input type="password" name="Pass">  </td></tr>
            <tr><td> Powtórz hasło: </td><td><input type="password" name="Pass2">  </td></tr>
            <tr><td> Konto bankowe: <span style="font-size: 10pt;"><br>(konieczne tylko dla konta profesjonalnego)</span>
            </td><td><input type="number" name="bank">  </td></tr>
  
            <tr><td align="center"><input type="submit" name="Zarejestruj się" value="Zarejestruj się"></td></tr>
             
        </form>
    </table>
    </td>
    <td>
            Chcesz poczuć trochę więcej emocji?<br>
            Weź udział w turnieju! <br>
            <form action="Turniej.php">
                <input type="submit" value="Chcę wziąć udział">
            </form>
    </td>   

    </tr></table>
    </body>
</html>