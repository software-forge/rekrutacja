<?php

    session_start();

    // przekierowanie do login.php, jeśli niezalogowany
    if(!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] == false)
    {
        header('Location: login_form.php');
        exit();
    }

    // ustalenie tożsamości zalogowanego usera
    $nick = '';
    $email = '';

    if(isset($_SESSION['nick']))
        $nick = $_SESSION['nick'];

    if(isset($_SESSION['email']))
        $email = $_SESSION['email'];

    $message = '';
    if(isset($_GET['activated']) and $_GET['activated'] == true)
        $message = '<p style="color: green;">Dziękujemy za aktywację</p>';
    
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Strona główna</title>
		<link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body class="body">
        <?php
            echo('Zalogowano jako: '.$nick.' <a href="logout.php" class="hyperlink">[WYLOGUJ]</a>');
            echo($message);
        ?>
    </body>
</html>

