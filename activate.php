<?php

    /*
        Skrypt implementujący logikę aktywacji konta uzytkownika
    */

    session_start();

    // Jezeli nieustawiona zmienna 'user_id' - przekierowanie do index.php
    if(!isset($_GET['user_id']))
        header('Location: index.php');
    
    $user_id = $_GET['user_id'];

    /*
        1. Sprawdzenie, czy w bazie jest uzytkownik o takim user_id, jezeli jest to zalogowanie go (ustawienie zmiennych sesji)
    */

    require('db_credentials.php');

    $connection = mysqli_connect($db_servername, $db_username, $db_password);

    if(!$connection)
    {
        echo('Błąd połączenia z bazą: '.mysqli_connect_errno());
        exit();
    }

    $query = 'SELECT * FROM `portal`.`users` WHERE user_id='.$user_id;

    $result = mysqli_query($connection, $query);

    if(!$result)
    {
        mysqli_close($connection);
        echo('Nie udało się odnaleźć uzytkownika o podanym user_id - błąd zapytania do bazy: '.mysqli_connect_errno());
        exit();
    }

    if(mysqli_num_rows($result) == 1)
    {
        // zalogowanie uzytkownika

        $_SESSION['logged_in'] = true;

        $row = mysqli_fetch_assoc($result);

        $_SESSION['nick'] = $row['nick'];
        $_SESSION['email'] = $row['email'];
    }
    else
    {
        // Błąd - znaleziono więcej niz jednego uzytkownika o podanym user_id, lub nie znaleziono zadnego (nie powinien wystąpić)
        mysqli_close($connection);
        echo('Nie udało się aktywować konta - błędne user_id');
        exit();
    }

    /*
        2. Aktywacja konta usera
    */

    $query = 'UPDATE `portal`.`users` SET `is_active` = \'1\' WHERE `users`.`user_id` = '.$user_id;

    $result = mysqli_query($connection, $query);

    if(!$result)
    {
        mysqli_close($connection);
        echo('Nie udało się aktywować konta - błąd zapytania do bazy: '.mysqli_connect_errno());
        exit();
    }

    mysqli_close($connection);

    /*
         3. Przekierowanie do index.php z komunikatem "Dziękujemy za aktywację"
    */

    header('Location: index.php?activated=true');
	
?>