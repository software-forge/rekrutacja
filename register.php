<?php

    /*
        Skrypt implementujący logikę dodawania nowego użytkownika
    */

	require('redirect.php');
	
    // Przekierowanie do index.php, jeżeli POST-em nic nie przyszło
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
		redirect(REGISTER_PAGE, INVALID_EMAIL);
    
    // Otwarcie połączenia z serwerem MySQL
    require('db_credentials.php');

    $connection = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

    // Obsługa błędu połączenia -> przekierowanie do register.php
    if(!$connection)
		redirect(REGISTER_PAGE, ERROR);

    // 1.2 Walidacja nicku = sprawdzenie, czy w bazie nie ma jeszcze takiego uzytkownika

    $query = 'SELECT * FROM `users` WHERE nick = \''.$nick.'\'';

    $result = mysqli_query($connection, $query);

    // Obsługa błędu zapytania -> przekierowanie do register.php
    if(!$result)
    {
        mysqli_close($connection);
        redirect(REGISTER_PAGE, ERROR);
    }

    // Taki użytkownik juz istnieje w bazie -> przekierowanie do register.php
    if(mysqli_num_rows($result) > 0)
    {
        mysqli_close($connection);
        redirect(REGISTER_PAGE, USER_EXISTS);
    }

    /*
        2. Dodanie użytkownika
    */

    // 2.1 Zahashowanie hasła
    $password_hash = password_hash($pass, PASSWORD_DEFAULT);

    // 2.2 Dodanie rekordu usera do tabeli (na razie is_active = 0)
    $query = 'INSERT INTO `users` 
        (`user_id`,
        `nick`,
        `password_hash`,
        `email`,
        `is_active`) VALUES (NULL, \''.$nick.'\', \''.$password_hash.'\', \''.$email.'\', \'0\')';

    $result = mysqli_query($connection, $query);

    // Obsługa błędu zapytania -> przekierowanie do register.php
    if(!$result)
    {
        mysqli_close($connection);
        redirect(REGISTER_PAGE, ERROR);
    }

    /*
        3. Wyciągnięcie z nowo utworzonego rekordu id_usera
    */

    $query = 'SELECT * FROM `users` WHERE nick=\''.$nick.'\'';

    $result = mysqli_query($connection, $query);

    // Obsługa błędu zapytania -> przekierowanie do register.php
    if(!$result)
    {
        mysqli_close($connection);
        redirect(REGISTER_PAGE, ERROR);
    }

    $row = array();

    // Spodziewam się dokładnie jednego rekordu (nick jest unikatowy)
    if(mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);
    }
    else
    {
        // Błąd - znaleziono więcej niż jeden rekord uzytkownika o takim nicku, lub nie znaleziono zadnego (nie powinien wystąpić)
        mysqli_close($connection);
        redirect(REGISTER_PAGE, ERROR);
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
		W systemach UNIX-owych zastąpić <adres_ip> właściwym adresem IP maszyny wirtualnej, na której stoi serwer
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
                                        color: darkgrey;
										text-decoration: none;
                                    }
                                    .hyperlink:hover
                                    {
                                        color: lightgrey;
                                        text-decoration: underline;
                                    }
									.hyperlink:visited
                                    {
                                        color: darkgrey;
										text-decoration: none;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="main-content">
                                    <p>Aby aktywować konto, kliknij w poniższy link:</p>
                                    <a class="hyperlink" href="'.$activate_url.'">Aktywuj konto</a>
                                </div>
                            </body>
                        </html>
                    ';
                        

    $mail_result = mail($email, 'Aktywacja konta', $mail_content, $content_type);

    if(!$mail_result)
        redirect(REGISTER_PAGE, ERROR);

    /*
        5. Przekierowanie na stronę rejestracji
    */

    redirect(REGISTER_PAGE, CHECK_INBOX);

?>