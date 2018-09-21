<?php

    /*
        Skrypt implementujący logikę wylogowania uzytkownika
    */

    session_start();

	/*
		1. Zwolnienie zmiennychh sesji
	*/
	
    session_unset();

	/*
		2. Przekierowanie do strony logowania z komunikatem "wylogowano"
	*/
	
	require('redirect.php');
	
    redirect(LOGIN_PAGE, LOGGED_OUT);

?>