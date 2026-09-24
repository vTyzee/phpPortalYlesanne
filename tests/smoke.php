<?php
declare(strict_types=1);
/* PHP CLI, no database required. This is NOT a code-coverage measurement. */
require __DIR__ . '/../inc/bootstrap.php';

$passed = 0;
function check($expected, $actual, string $label): void {
    global $passed;
    if ($expected !== $actual) {
        fwrite(STDERR, "FAIL: {$label}\nExpected: " . var_export($expected, true) . "\nActual: " . var_export($actual, true) . "\n");
        exit(1);
    }
    $passed++;
    echo "PASS: {$label}\n";
}

$key = [['id'=>1,'correct_option_id'=>20], ['id'=>2,'correct_option_id'=>24], ['id'=>3,'correct_option_id'=>27]];
check(['correct'=>3,'total'=>3], quiz_score($key, [1=>20,2=>24,3=>27]), 'Kõik vastused õiged');
check(['correct'=>1,'total'=>3], quiz_score($key, [1=>20,2=>99]), 'Osaliselt õiged vastused');
check(['correct'=>0,'total'=>3], quiz_score($key, []), 'Tühjad vastused');
check(['correct'=>0,'total'=>0], quiz_score([], []), 'Tühi test');
check(['correct'=>1,'total'=>3], quiz_score($key, [1=>20,2=>20,3=>20]), 'Teise küsimuse vastus ei anna punkte');

$questions = [
    ['id'=>1,'question'=>'Esimene küsimus?', 'options'=>[['id'=>20,'option_text'=>'Õige'],['id'=>21,'option_text'=>'Vale']]],
    ['id'=>2,'question'=>'Teine küsimus?', 'options'=>[['id'=>23,'option_text'=>'Vale'],['id'=>24,'option_text'=>'Õige 2']]],
    ['id'=>3,'question'=>'Kolmas küsimus?', 'options'=>[['id'=>27,'option_text'=>'Õige 3'],['id'=>28,'option_text'=>'Vale']]],
];
$feedback = quiz_feedback($questions, $key, [1=>20,2=>23]);
check(3, count($feedback), 'Tagasiside küsimuste arv');
check(true, $feedback[0]['correct'], 'Õige vastuse tagasiside');
check(false, $feedback[1]['correct'], 'Vale vastuse tagasiside');
check(false, $feedback[2]['correct'], 'Vastamata küsimuse tagasiside');
check('Õige 2', $feedback[1]['answer'], 'Vale vastuse juures näidatakse õiget vastust');
check('Õige 3', $feedback[2]['answer'], 'Vastamata küsimuse juures näidatakse õiget vastust');
check('Teine küsimus?', $feedback[1]['question'], 'Tagasisides säilib küsimuse tekst');
check([], quiz_feedback([], [], []), 'Tühja testi tagasiside');

check('&lt;script&gt;', e('<script>'), 'HTML-i väljundi kodeerimine');
check('&quot;&amp;&#039;', e('"&\''), 'Jutumärkide ja ampersandi kodeerimine');
check(false, is_logged_in(), 'Külaline ei ole sisse logitud');
check(false, is_admin(), 'Külaline pole admin');
$_SESSION['user_id']=42;$_SESSION['role']='user';
check(true, is_logged_in(), 'Õpilane on sisse logitud');
check(false, is_admin(), 'Õpilane ei ole admin');
$_SESSION['role']='admin';
check(true, is_admin(), 'Administraatori roll');
unset($_SESSION['user_id'],$_SESSION['role']);
$token = csrf_token();
check(true, strlen($token)===64, 'CSRF tokeni pikkus');
$_POST=['csrf'=>$token];check(true, csrf_valid(), 'Korrektne CSRF token');
$_POST=['csrf'=>'vale'];check(false, csrf_valid(), 'Vale CSRF token');
$_POST=[];check(false, csrf_valid(), 'Puuduv CSRF token');
check(true, str_contains(url('lesson?id=1'), 'lesson?id=1'), 'Suhteline õppematerjali URL');
echo "Kokku {$passed}/{$passed} sõltumatut kontrolli läbitud. MySQL-i integratsioon ja koodikate ei ole siin mõõdetud.\n";
