Konfiguracja e-maila w pakiecie XAMPP (aby skrypt register.php wysyłał maila z linkiem aktywacyjnym):

******************************* Windows: *******************************

Pod Windowsem możemy skonfigurować XAMPP-a tak, żeby wbudowany w paczkę program sendmail wysyłał e-maile np. poprzez gmail.

W tym celu należy edytować pliki konfiguracyjne php.ini oraz sendmail.ini

Plik php.ini (prawdopodobnie pod ścieżką: C:\xampp\php\php.ini):

1. Odnaleźć w pliku sekcję [mail function]
2. Nadać odpowiednie wartości zmiennym (i ewentualnie odkomentować je):

	SMTP=smtp.gmail.com
	smtp_port=587
	sendmail_from = <uzytkownik>@gmail.com	-> oczywiście <uzytkownik> zastępujemy swoją nazwą użytkownika w gmailu
	sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"

Plik sendmail.ini (prawdopodobnie pod ścieżką: C:\xampp\sendmail\sendmail.ini)

1. Odnaleźć w pliku sekcję [sendmail]
2. Nadać odpowiednie wartości zmiennym (i ewentualnie odkomentować je):
	
	smtp_server=smtp.gmail.com
	smtp_port=587
	error_logfile=error.log
	debug_logfile=debug.log
	auth_username=<uzytkownik>@gmail.com	-> oczywiście <uzytkownik> zastępujemy swoją nazwą użytkownika w gmailu
	auth_password=<haslo_konta_gmail>		-> tutaj wpisujemy hasło do swojego konta gmail
	force_sender=<uzytkownik>@gmail.com		-> jw.

Aby zmiany zostały zastosowane, należy zrestartować wszystkie usługi w pakiecie XAMPP.
	
W ustawieniach swojego konta gmail należy zezwolić na dostęp mniej bezpiecznym aplikacjom, inaczej wiadomości nie będą przechodzić,
a sam gmail będzie wyrzucał krytyczne alerty bezpieczeństwa.
	
************************************************************************
	
******************* Systemy UNIX-owe (Linux, macOS): *******************

W pierwszej kolejności, może zaistnieć potrzeba zainstalowania programu sendmail na wirtualnym serwerze, jeżeli nie był zainstalowany oryginalnie.

W tym celu należy wykonać w konsoli polecenie:

sudo apt-get install sendmail

Po poprawnym wykonaniu instalacji należy również edytować plik php.ini (/opt/lampp/php/php.ini)

W pliku php.ini odnajdujemy sekcję [mail function], zakomentowujemy zmienne opisane jako "For Win32 only" i odkomentowujemy
zmienną sendmail_path (jedyna w sekcji opisana jako "For Unix only"). Zmiennej tej nadajemy wartość: /usr/sbin/sendmail -t -i

Podobnie jak w przypadku konfiguracji pod Windowsem, należy zrestartować wszystkie usługi XAMPP-a, aby zmiany zostały wprowadzone.

Wiadomości wysyłane przez UNIX-owego sendmaila często lądują w skrzynce ze spamem.

************************************************************************

WAŻNE:
W zależności od platformy, należy odkomentować (i ewentualnie zmodyfikować) odpowiednią wersję zmiennej $activate_url w skrypcie register.php
Dodatkowa instrukcja do tej czynności jest zawarta w komentarzu w samym skrypcie.

