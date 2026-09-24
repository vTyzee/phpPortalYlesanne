<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') {http_response_code(403);exit('CLI only');}
require __DIR__.'/../inc/db.php';
function ask(string $label): string {echo $label;return trim((string)fgets(STDIN));}
$name=ask("Administraatori nimi: ");
$email=ask("Administraatori e-post: ");
$password=ask("Parool (vähemalt 8 tähemärki): ");
if (mb_strlen($name)<2 || !filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<8) {fwrite(STDERR,"Vigane nimi, e-post või parool.\n");exit(1);}
try {
 $q=db()->prepare("INSERT INTO users(username,email,password_hash,role) VALUES(?,?,?,'admin')");
 $q->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
 echo "Administraatori konto loodud. Logi veebilehel sisse.\n";
} catch(PDOException $e) {fwrite(STDERR,"Konto loomine ebaõnnestus. Kas e-post on juba kasutusel või andmebaas puudub?\n");exit(1);}
