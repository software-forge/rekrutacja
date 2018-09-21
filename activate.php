<?php

    /*
        Skrypt implementujący logikę aktywacji konta użytkownika
    */

    session_start();

    // Jeżeli nieustawiona zmienna 'user_id' - przekierowanie do index.php
    if(!isset($_GET['user_id']))
        header('Location: index.php');
    
    $user_id = $_GET['user_id'];

    /*
        1. Sprawdzenie, czy w bazie jest uzytkownik o takim user_id, jeżeli jest to zalogowanie go
    */

    require('db_credentials.php');

    $connection = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

	// Błąd połączenia z bazą -> wyświetlenie komunikatu
    if(!$connection)
    {
        echo('Błąd połączenia z bazą: '.mysqli_errno($connection));
        exit();
    }

    $query = 'SELECT * FROM `users` WHERE user_id='.$user_id;

    $result = mysqli_query($connection, $query);

	// Błąd zapytania -> wyświetlenie komunikatu
    if(!$result)
    {
        mysqli_close($connection);
        echo('Nie udało się odnaleźć uzytkownika o podanym user_id - błąd zapytania do bazy: '.mysqli_errno($connection));
        exit();
    }

	// Znaleziono użytkownika o takim user_id (sytuacja prawidłowa)
    if(mysqli_num_rows($result) > 0)
    {
        // Zalogowanie użytkownika

        $_SESSION['logged_in'] = true;

        $row = mysqli_fetch_assoc($result);

        $_SESSION['nick'] = $row['nick'];
        $_SESSION['email'] = $row['email'];
    }
    else
    {
        // Błąd - nie znaleziono użytkownika o podanym user_id (nie powinien wystąpić)
        mysqli_close($connection);
        echo('Nie udało się aktywować konta - błędne user_id');
        exit();
    }

    /*
        2. Aktywacja konta usera
    */

    $query = 'UPDATE `users` SET `is_active` = \'1\' WHERE `users`.`user_id` = '.$user_id;

    $result = mysqli_query($connection, $query);
	
	mysqli_close($connection);

	// Błąd zapytania -> wyświetlenie komunikatu
    if(!$result)
    {
        echo('Nie udało się aktywować konta - błąd zapytania do bazy: '.mysqli_errno($connection));
        exit();
    }

    /*
         3. Przekierowanie do index.php z komunikatem "Dziękujemy za aktywację"
    */

    header('Location: index.php?activated=true');
	
?>