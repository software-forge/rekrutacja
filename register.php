<?php

    /*
        Skrypt implementujący logikę dodawania nowego uzytkownika
    */

    // Przekierowanie do index.php, jezeli POST-em nic nie przyszło
    if(!isset($_POST['email']) or !isset($_POST['nick']) or !isset($_POST['password']))
    {
        header('Location: index.php');
        exit();
    }
        
    $email = $_POST['email'];
    $nick = $_POST['nick'];
    $pass = $_POST['password'];

    /*
        1. Walidacja danych
    */

    // 1.1 Walidacja adresu e-mail

    // Podany tekst nie jest poprawnym adresem e-mail -> przekierowanie do register.php
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        header("Location: register_form.php?message=invalid_email");
        exit();
    }
    
    // Otwarcie połączenia z serwerem MySQL
    require('db_credentials.php');

    $connection = mysqli_connect($db_servername, $db_username, $db_password);

    // Obsługa błędu połączenia -> przekierowanie do register.php
    if(!$connection)
    {
        header('Location: register_form.php?message=other_error');
        exit();
    }

    // 1.2 Walidacja nicku = sprawdzenie, czy w bazie nie ma jeszcze takiego uzytkownika

    $query = 'SELECT * FROM `portal`.`users` WHERE nick = \''.$nick.'\'';

    $result = mysqli_query($connection, $query);

    // Obsługa błędu zapytania -> przekierowanie do register.php
    if(!$result)
    {
        mysqli_close($connection);
        header('Location: register_form.php?message=other_error');
        exit();
    }

    // Taki uzytkownik juz istnieje w bazie -> przekierowanie do register.php
    if(mysqli_num_rows($result) > 0)
    {
        mysqli_close($connection);
        header('Location: register_form.php?message=user_exists');
        exit();
    }

    /*
        2. Dodanie usera
    */

    // 2.1 Zahashowanie hasła
    $password_hash = password_hash($pass, PASSWORD_DEFAULT);

    // 2.2 Dodanie rekordu usera do tabeli (na razie aktywny = false)
    $query = 'INSERT INTO `portal`.`users` 
        (`user_id`,
        `nick`,
        `password_hash`,
        `email`,
        `is_active`) VALUES (NULL, \''.$nick.'\', \''.$password_hash.'\', \''.$email.'\', \'0\')';

    //echo($query);
    //exit();

    $result = mysqli_query($connection, $query);

    // Obsługa błędu zapytania -> przekierowanie do register.php
    if(!$result)
    {
        mysqli_close($connection);
        header('Location: register_form.php?message=other_error');
        exit();
    }

    /*
        3. Wyciągnięcie z nowo utworzonego rekordu id_usera
    */

    $query = 'SELECT * FROM `portal`.`users` WHERE nick=\''.$nick.'\'';

    $result = mysqli_query($connection, $query);

    // Obsługa błędu zapytania -> przekierowanie do register.php
    if(!$result)
    {
        mysqli_close($connection);
        header('Location: register_form.php?message=other_error');
        exit();
    }

    $row = array();

    // Spodziewam się dokładnie jednego rekordu (nick jest unikatowy)
    if(mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);
    }
    else
    {
        // Błąd - znaleziono więcej niz jeden rekord uzytkownika o takim nicku, lub nie znaleziono zadnego (nie powinien wystąpić)
        mysqli_close($connection);
        header('Location: register_form.php?message=other_error');
        exit();
    }

    $user_id = $row['user_id'];

    // Zamknięcie połączenia z serwerem MySQL
    mysqli_close($connection);

    /*
        4. Wysłanie maila z linkiem aktywacyjnym
    */
    
    $content_type = 'Content-type: text/html; charset=UTF-8 \r\n';

	/*
		WAŻNE!!!
		Odkomentować właściwą zmienną $activate_url w zależności od systemu operacyjnego na którym zainstalowany jest pakiet XAMPP
		W systemach UNIX-owych zastąpić <adres_ip> właściwym adresem IP wirtualnego serwera
	*/
	
	$activate_url = 'localhost/rekrutacja/activate.php?user_id='.$user_id; // W pakiecie XAMPP na Windowsie
	//$activate_url = '<adres_ip>/rekrutacja/activate.php?user_id='.$user_id; // W pakiecie XAMPP na systemach UNIX-owych
	
    $mail_content = '   <html>
                            <head>
                                <title>Aktywacja konta</title>
                                <style>
                                    .main-content
                                    {
                                        text-align: center;
                                    }
                                    .hyperlink
                                    {
                                        color: blue;
                                    }
                                    .hyperlink:hover
                                    {
                                        color: blue;
                                        background-color: lime;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="main-content">
                                    <p>Aby aktywować konto, kliknij w ponizszy link:</p>
                                    <a class="hyperlink" href="'.$activate_url.'">Aktywuj konto</a>
                                </div>
                            </body>
                        </html>
                    ';
                        

    $mail_result = mail($email, 'Aktywacja konta', $mail_content, $content_type);

    if(!$mail_result)
    {
        header("Location: register_form.php?message=mail_error");
        exit();
    }

    /*
        5. Przekierowanie na stronę rejestracji
    */

    header("Location: register_form.php?message=check_inbox");

?>