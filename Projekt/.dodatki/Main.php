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
        Zaloguj się: 
        <form action="logowanieDB.php"  method="post" >
                Login: <input type="text" name="LOGINDB"> <br>
                Hasło: <input type="password" name="PASSWORDDB"> <br>
                <input type="submit" name="Zaloguj" value="Zaloguj"> 
        </form>
       <!-- <img src="test.png" width="500" height="600" alt="Hehehehehehihihihi"> -->
   <!--    <a href="https://www.w3schools.com">Visit W3Schools</a> -->
        <br>
        <hr>
        <br>
        <pre>
            Ten tekst już jest    przygotowany
            Liczba      spacji  jest
            zdecydowanie 
                odpowiednia
        </pre>
        Nie masz jeszcze konta? <br> 
       <form action="rejestracja.php" method="post" >
            <input list="konto" name="TypKonta" id="TypKonta" >
            <datalist id="konto">
              <option value="Zwykłe">
              <option value="Profesjonalne">
              <option value="Drużynowe">
            </datalist>
            <br>
            <!-- TODO opcje dodawania różnych rodzajów kont -->
            Login: <input type="text" name="Log"> <br>
            Hasło: <input type="password" name="Pass"> <br>
            Powtórz hasło: <input type="password" name="Pass2"> <br>
            <input type="submit" name="Zarejestruj się" value="Zarejestruj się"> 
        </form>

        <!-- Co to robi? XD -->
        <form action="/action_page.php" method="get">
        </form>
    </body>
</html>