<?php

    /*
         Skrypt implementujący logikę autoryzacji uzytkownika
    */

    session_start();

    // Przekierowanie do index.php, jezeli POST-em nic nie przyszło
    if(!isset($_POST['nick']) or !isset($_POST['password']))
    {
        header('Location: index.php');
        exit();
    }

    $nick = $_POST['nick'];
    $pass = $_POST['password'];

    /*
        1. Sprawdzenie, czy w bazie jest aktywny uzytkownik o przekazanym nicku
    */

    require('db_credentials.php');

    $connection = mysqli_connect($db_servername, $db_username, $db_password);

    if(!$connection)
    {
        header('Location: login_form.php?message=other_error');
        exit();
    }

    $query = 'SELECT * FROM `portal`.`users` WHERE nick=\''.$nick.'\'';

    $result = mysqli_query($connection, $query);

    if(!$result)
    {
        mysqli_close($connection);
        header('Location: login_form.php?message=other_error');
        exit();
    }

    $users_found = mysqli_num_rows($result);

    $email;
    $password_hash;

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

        if($users_found > 1)
        {
            // Błąd - więcej niz jeden user o takim nicku (nie powinien wystąpić)
            header('Location: login_form.php?message=other_error');
            exit();
        }

        // User nie istnieje - odmowa dostępu
        //echo('User nie odnaleziony'); // debug
        header('Location: login_form.php?message=access_denied');
        exit();
    }

    if(!$is_active)
    {
        // konto usera nie aktywowane - odmowa dostępu
        mysqli_close($connection);
        //echo('Konto nieaktywne'); // debug
        header('Location: login_form.php?message=access_denied');
        exit();
    }

    mysqli_close($connection);

    /*
        2. Weryfikacja hasła uzytkownika
    */

    $verify = password_verify($pass, $password_hash);

    if(!$verify)
    {
        // niepoprawne hasło - odmowa dostępu
        header('Location: login_form.php?message=access_denied');
        exit();
    }

    /*
        3. Zalogowanie uzytkownika
    */

    $_SESSION['logged_in'] = true;

    $_SESSION['nick'] = $nick;
    $_SESSION['email'] = $email;

    header('Location: index.php');

?>