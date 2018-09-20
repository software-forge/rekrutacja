<?php

    /*
        Skrypt implementujący logikę wylogowania uzytkownika
    */

    session_start();

    session_unset();

    header('Location: login_form.php?message=logged_out');

?>