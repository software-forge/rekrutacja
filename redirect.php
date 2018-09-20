<?php

	// Definicje wiadomości GET
	define('INVALID_EMAIL', 'invalid_email');
	define('USER_EXISTS', 'user_exists');
	define('CHECK_INBOX', 'check_inbox');
	define('ACCESS_DENIED', 'access_denied');
	define('LOGGED_OUT', 'logged_out');
	define('ERROR', 'error');
	
	// Definicje nazw plików
	define('LOGIN_PAGE', 'login_form.php');
	define('REGISTER_PAGE', 'register_form.php');
	
	// Funkcja przekierowująca do panelu logowania z przekazaniem odpowiedniej wiadomości GET
	function redirect($file, $message)
	{
		header('Location: '.$file.'?message='.$message);
        exit();
	}
	
?>