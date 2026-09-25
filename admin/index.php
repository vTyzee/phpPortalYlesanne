<?php
declare(strict_types=1);
require __DIR__.'/../inc/bootstrap.php';
require __DIR__.'/../inc/db.php';
foreach (['Subject','Lesson','Quiz','Comments','Auth','Bookmark'] as $model) require __DIR__.'/../model/'.$model.'.php';
require __DIR__.'/../admin/AdminController.php';
$path=trim((string)parse_url($_SERVER['REQUEST_URI']??'',PHP_URL_PATH),'/');
$path=substr($path,strrpos($path,'/')+1);
if ($path==='admin'||$path==='index.php')$path='';
try {switch($path){
  case '':AdminController::home();break;
  case 'login':AdminController::login();break;
  case 'logout':AdminController::logout();break;
  case 'lessons':AdminController::lessons();break;
  case 'edit':AdminController::edit();break;
  case 'delete':AdminController::delete();break;
  case 'quiz':AdminController::quiz();break;
  case 'comment-delete':AdminController::deleteComment();break;
  default:http_response_code(404);render('error',['title'=>'Lehte ei leitud','message'=>'Kontrolli veebiaadressi.']);
}} catch(PDOException $e){error_log((string)$e);http_response_code(503);render('error',['title'=>'Andmebaasiga ühendamine ei õnnestunud','message'=>'Kontrolli MySQL-i ja andmebaasi seadistust.']);}
