<?php
    
    $message = '';
    if(isset($_GET['message']))
        $message = $_GET['message'];

?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <link href="styles.css" rel="stylesheet" type="text/css"/>
        <title>Logowanie</title>
    </head>
    <body>
        <div class="form">
            <h3>Logowanie</h3>
            <hr>
                    <form action="login.php" method="post">
                        <div>
                            Nick: <input type="text" name="nick"/>
                            <br><br>
                            Hasło: <input type="password" name="password"/>
                            <br><br>
                            </div>
                            <input type="submit" value="Zaloguj"/>
                            <br><br>
                    </form>
                    <a href="register_form.php">Zarejestruj się</a>
                <?php
                    if($message === 'logged_out')
                        echo('<p style="color: green;">Wylogowano</p>');
                    if($message === 'access_denied')
                        echo('<p style="color: red;">Nieprawidłowe dane logowania lub konto nieaktywne.</p>');
                    if($message === 'error')
                        echo('<p style="color: red;">Wystąpił błąd, skontaktuj się z administratorem.</p>');
                ?>
        </div>
    </body>
</html>