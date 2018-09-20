<?php

    /*
        Skrypt implementujący logikę wylogowania uzytkownika
    */

    session_start();

    session_unset();

	require('redirect.php');
	
    redirect(LOGIN_PAGE, LOGGED_OUT);

?>