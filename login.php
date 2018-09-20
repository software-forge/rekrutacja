<?php

    /*
         Skrypt implementujący logikę autoryzacji uzytkownika
    */

	session_start();
	
	require('redirect.php');
	
    // Przekierowanie do index.php, jezeli POST-em nic nie przyszło
    if(!isset($_POST['nick']) or !isset($_POST['password']))
    {
        header('Location: index.php');
        exit();
    }

    $nick = $_POST['nick'];
    $pass = $_POST['password'];

    /*
        1. Sprawdzenie, czy w bazie jest aktywny użytkownik o przekazanym nicku
    */

    require('db_credentials.php');

    $connection = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

    if(!$connection)
		redirect(LOGIN_PAGE, ERROR);

    $query = 'SELECT * FROM `users` WHERE nick=\''.$nick.'\'';

    $result = mysqli_query($connection, $query);

    if(!$result)
    {
        mysqli_close($connection);
        redirect(LOGIN_PAGE, ERROR);
    }

    $users_found = mysqli_num_rows($result);

    $email = '';
    $password_hash = '';

    $is_active = 0;
    if($users_found == 1)
    {
        $row = mysqli_fetch_assoc($result);

        $is_active = $row['is_active'];
        $email = $row['email'];
        $password_hash = $row['password_hash'];
    }
    else
    {
        mysqli_close($connection);

		// Błąd - więcej niż jeden użytkownik o takim nicku (nie powinien wystąpić)
        if($users_found > 1)
			redirect(LOGIN_PAGE, ERROR);
		
        // Użytkownik o przekazanym nicku nie istnieje -> odmowa dostępu
        redirect(LOGIN_PAGE, ACCESS_DENIED);
    }

    if(!$is_active)
    {
        // Konto użytkownika nieaktywne -> odmowa dostępu
        mysqli_close($connection);
        redirect(LOGIN_PAGE, ACCESS_DENIED);
    }

    mysqli_close($connection);

    /*
        2. Weryfikacja hasła uzytkownika
    */

    $verify = password_verify($pass, $password_hash);

	// Weryfikacja hasła negatywna -> odmowa dostępu
    if(!$verify)
		redirect(LOGIN_PAGE, ACCESS_DENIED);

    /*
        3. Zalogowanie uzytkownika
    */

    $_SESSION['logged_in'] = true;

    $_SESSION['nick'] = $nick;
    $_SESSION['email'] = $email;

    header('Location: index.php');

?>