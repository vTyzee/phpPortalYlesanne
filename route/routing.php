<?php
declare(strict_types=1);
$path=trim((string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$prefix=trim(BASE_PATH,'/');
if ($prefix !== '' && ($path===$prefix || str_starts_with($path,$prefix.'/'))) $path=trim(substr($path,strlen($prefix)),'/');
if ($path==='index.php') $path='';
try {
    switch ($path) {
        case '': Controller::home();break;
        case 'subjects': Controller::subjects();break;
        case 'lessons': Controller::lessons();break;
        case 'lesson': Controller::lesson();break;
        case 'image': Controller::image();break;
        case 'quiz': Controller::quiz();break;
        case 'comment': Controller::comment();break;
        case 'bookmark': Controller::bookmark();break;
        case 'register': Controller::register();break;
        case 'registerAnswer': Controller::registerAnswer();break;
        case 'dashboard': Controller::dashboard();break;
        default: http_response_code(404);render('error',['title'=>'Lehte ei leitud','message'=>'Kontrolli aadressi või mine tagasi avalehele.']);
    }
} catch (PDOException $exception) {
    error_log((string)$exception);
    http_response_code(503);
    render('error',['title'=>'Andmebaasiga ühendamine ei õnnestunud','message'=>'Kontrolli, et MySQL töötab ja oled importinud faili database/opi_eestis.sql.']);
}
