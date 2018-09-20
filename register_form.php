<?php

    $message = '';
    if(isset($_GET['message']))
        $message = $_GET['message'];

?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Rejestracja</title>
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <div class="form">
            <h3>Rejestracja</h3>
            <hr>
                <form action="register.php" method="post">
                    E-Mail: <input type="text" name="email"/>
                    <br><br>
                    Nick: <input type="text" name="nick"/>
                    <br><br>
                    Hasło: <input type="password" name="password"/>
                    <br><br>
                    <input type="submit" value="Zarejestruj"/>
                </form>
                <?php
                    if($message === 'invalid_email')
                        echo('<p style="color: red;">Podaj prawidłowy adres e-mail!</p>');
                    if($message === 'user_exists')
                        echo('<p style="color: red;">Uzytkownik o takim nicku juz istnieje, wybierz inny nick.</p>');
                    if($message === 'other_error')
                        echo('<p style="color: red;">Wystąpił błąd, skontaktuj się z administratorem.</p>');
                    if($message === 'check_inbox')
                        echo('<p style="color: green;">Wysłano e-mail z linkiem aktywacyjnym, sprawdź swoją skrzynkę pocztową.</p>');
                ?>
        </div>
    </body>
</html>