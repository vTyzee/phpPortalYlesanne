<?php
declare(strict_types=1);
require __DIR__.'/../inc/bootstrap.php';
function assert_same($expected,$actual,string $label):void {if($expected!==$actual){fwrite(STDERR,"FAIL: $label\n");exit(1);}echo "PASS: $label\n";}
$key=[['id'=>1,'correct_option_id'=>20],['id'=>2,'correct_option_id'=>24],['id'=>3,'correct_option_id'=>27]];
assert_same(['correct'=>3,'total'=>3],quiz_score($key,[1=>20,2=>24,3=>27]),'Kõik vastused õiged');
assert_same(['correct'=>1,'total'=>3],quiz_score($key,[1=>20,2=>99]),'Osaliselt õiged vastused');
assert_same(['correct'=>0,'total'=>3],quiz_score($key,[]),'Tühjad vastused');
assert_same('&lt;script&gt;',e('<script>'),'HTML-i väljundi turvaline kodeerimine');
$token=csrf_token();$_POST=['csrf'=>$token];assert_same(true,csrf_valid(),'Korrektne CSRF token');
$_POST=['csrf'=>'vale'];assert_same(false,csrf_valid(),'Vale CSRF token');
echo "6/6 kontrolli läbitud. Andmebaasipõhised integratsioonitestid vajavad töötavat MySQL-i.\n";
