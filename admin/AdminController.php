<?php
declare(strict_types=1);
class AdminController {
    public static function home(): void {
        if (!is_logged_in()) {render('login',['title'=>'Logi sisse']);return;}
        if (!is_admin()) {redirect('dashboard');}
        render('admin-home',['title'=>'Halduspaneel','lessonCount'=>Lesson::count(),'subjects'=>Subject::all()]);
    }
    public static function login(): void {
        if ($_SERVER['REQUEST_METHOD']!=='POST'||!csrf_valid()) {http_response_code(400);render('error',['title'=>'Vorm aegus','message'=>'Proovi uuesti.']);return;}
        if (!Auth::login((string)($_POST['email']??''),(string)($_POST['password']??''))) {http_response_code(401);render('login',['title'=>'Logi sisse','loginError'=>'Vale e-post või parool.']);return;}
        redirect(is_admin()?'admin/':'dashboard');
    }
    public static function logout(): void {
        if ($_SERVER['REQUEST_METHOD']!=='POST'||!csrf_valid()) {http_response_code(400);render('error',['title'=>'Vorm aegus','message'=>'Proovi uuesti.']);return;}
        Auth::logout();redirect();
    }
    public static function lessons(): void {
        require_admin();render('admin-lessons',['title'=>'Õppematerjalide haldus','lessons'=>Lesson::allForAdmin()]);
    }
    private static function imageFromForm(): array {
        $file=$_FILES['cover']??null;
        if (!$file||$file['error']===UPLOAD_ERR_NO_FILE) return [null,null,null];
        if ($file['error']!==UPLOAD_ERR_OK||$file['size']>2*1024*1024||$file['size']<=0||!is_uploaded_file($file['tmp_name'])) return [null,null,'Pilt peab olema JPG, PNG, GIF või WebP ning kuni 2 MB.'];
        $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!in_array($mime,['image/jpeg','image/png','image/webp','image/gif'],true)||!@getimagesize($file['tmp_name']))return [null,null,'Vali sobiv pildifail (JPG, PNG, GIF või WebP).'];
        return [file_get_contents($file['tmp_name']),$mime,null];
    }
    public static function edit(): void {
        require_admin();$id=(int)($_SERVER['REQUEST_METHOD']==='POST'?($_POST['id']??0):($_GET['id']??0));
        $lesson=$id?Lesson::find($id,false):null;
        if ($id && !$lesson) {http_response_code(404);render('error',['title'=>'Õppetundi ei leitud','message'=>'Vali teine õppematerjal.']);return;}
        $error=null;
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            if (!csrf_valid()) {http_response_code(400);render('error',['title'=>'Vorm aegus','message'=>'Proovi uuesti.']);return;}
            $title=trim((string)($_POST['title']??''));$summary=trim((string)($_POST['summary']??''));$body=trim((string)($_POST['body']??''));
            $level=(string)($_POST['level']??'');$duration=filter_var($_POST['duration_min']??null,FILTER_VALIDATE_INT);
            $subject=filter_var($_POST['subject_id']??null,FILTER_VALIDATE_INT);
            $published=isset($_POST['published'])?1:0;
            [$image,$mime,$error]=self::imageFromForm();
            if (!$error && (mb_strlen($title)<3||mb_strlen($title)>255||mb_strlen($summary)<10||mb_strlen($summary)>320||mb_strlen($body)<20||!in_array($level,['Algaja','Kesktase','Edasijõudnu'],true)||!$duration||$duration>240||!$subject||!Subject::find($subject))) $error='Kontrolli pealkirja, lühikirjeldust, õppematerjali, taset ja õppeainet.';
            if (!$error) {
                if ($id) {
                    $sql='UPDATE lessons SET title=?,summary=?,body=?,level=?,duration_min=?,subject_id=?,published=?';
                    $params=[$title,$summary,$body,$level,$duration,$subject,$published];
                    if ($image!==null) {$sql.=',cover_image=?,cover_mime=?';$params[]=$image;$params[]=$mime;}
                    $sql.=' WHERE id=?';$params[]=$id;
                    $q=db()->prepare($sql);$q->execute($params);
                } else {
                    $q=db()->prepare('INSERT INTO lessons (title,summary,body,level,duration_min,subject_id,published,cover_image,cover_mime,author_id) VALUES (?,?,?,?,?,?,?,?,?,?)');
                    $q->execute([$title,$summary,$body,$level,$duration,$subject,$published,$image,$mime,(int)$_SESSION['user_id']]);
                }
                flash($id?'Õppematerjal on uuendatud.':'Õppematerjal on lisatud.');redirect('admin/lessons');
            }
            $lesson=array_merge($lesson?:[],['id'=>$id,'title'=>$title,'summary'=>$summary,'body'=>$body,'level'=>$level,'duration_min'=>$duration,'subject_id'=>$subject,'published'=>$published]);
        }
        render('admin-edit',['title'=>$id?'Muuda õppematerjali':'Lisa õppematerjal','lesson'=>$lesson,'subjects'=>Subject::all(),'formError'=>$error]);
    }
    public static function quiz(): void {
        require_admin();
        $lessonId=(int)($_SERVER['REQUEST_METHOD']==='POST'?($_POST['lesson_id']??0):($_GET['lesson_id']??0));
        $lesson=Lesson::find($lessonId,false);
        if (!$lesson) {http_response_code(404);render('error',['title'=>'Õppetundi ei leitud','message'=>'Vali teine õppematerjal.']);return;}
        $error=null;
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            if (!csrf_valid()) {http_response_code(400);render('error',['title'=>'Vorm aegus','message'=>'Proovi uuesti.']);return;}
            if (($_POST['action']??'')==='delete') {
                $questionId=filter_input(INPUT_POST,'question_id',FILTER_VALIDATE_INT);
                if ($questionId) {$q=db()->prepare('DELETE FROM quiz_questions WHERE id=? AND lesson_id=?');$q->execute([$questionId,$lessonId]);flash('Küsimus on kustutatud.');}
                redirect('admin/quiz?lesson_id='.$lessonId);
            }
            $question=trim((string)($_POST['question']??''));$options=$_POST['options']??[];
            $options=is_array($options)?array_map(static fn($v)=>is_string($v)?trim($v):'',array_slice($options,0,3)):[];
            $correct=filter_var($_POST['correct']??null,FILTER_VALIDATE_INT);
            if (mb_strlen($question)<5||mb_strlen($question)>500||count($options)!==3||count(array_filter($options,static fn($v)=>mb_strlen($v)>0&&mb_strlen($v)<=300))!==3||$correct===false||$correct<0||$correct>2) {
                $error='Lisa küsimus ja kolm vastusevarianti ning vali üks õige vastus.';
            } else {
                $pdo=db();$pdo->beginTransaction();
                try {
                    $q=$pdo->prepare('INSERT INTO quiz_questions(lesson_id,question) VALUES(?,?)');$q->execute([$lessonId,$question]);
                    $qid=(int)$pdo->lastInsertId();$o=$pdo->prepare('INSERT INTO quiz_options(question_id,option_text,is_correct) VALUES(?,?,?)');
                    foreach ($options as $i=>$option) $o->execute([$qid,$option,(int)($i===$correct)]);
                    $pdo->commit();flash('Küsimus on lisatud.');redirect('admin/quiz?lesson_id='.$lessonId);
                } catch(Throwable $e) {$pdo->rollBack();throw $e;}
            }
        }
        render('admin-quiz',['title'=>'Testi haldamine','lesson'=>$lesson,'questions'=>Quiz::questions($lessonId),'formError'=>$error]);
    }
    public static function delete(): void {
        require_admin();
        if ($_SERVER['REQUEST_METHOD']!=='POST'||!csrf_valid()) {http_response_code(400);render('error',['title'=>'Vorm aegus','message'=>'Proovi uuesti.']);return;}
        $id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
        if ($id) {$q=db()->prepare('DELETE FROM lessons WHERE id=?');$q->execute([$id]);flash('Õppematerjal on kustutatud.');}
        redirect('admin/lessons');
    }
}
