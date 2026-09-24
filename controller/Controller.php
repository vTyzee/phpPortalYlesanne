<?php
declare(strict_types=1);
class Controller {
    public static function home(): void { render('home',['subjects'=>Subject::all(),'lessons'=>Lesson::latest(),'lessonCount'=>Lesson::count(),'title'=>'Õppimine algab siit']); }
    public static function subjects(): void { render('subjects',['subjects'=>Subject::all(),'title'=>'Õppeained']); }
    public static function lessons(): void {
        $subject=filter_input(INPUT_GET,'subject',FILTER_VALIDATE_INT) ?: null;
        $subjectData=$subject ? Subject::find($subject):null;
        if ($subject!==null && !$subjectData) {http_response_code(404);render('error',['title'=>'Õppeainet ei leitud','message'=>'Vali mõni teine õppeaine.']);return;}
        $search=mb_substr(trim((string)($_GET['q']??'')),0,100);
        $level=(string)($_GET['level']??'');
        render('lessons',['lessons'=>Lesson::list($subject,$search,$level),'subjects'=>Subject::all(),'selectedSubject'=>$subject,'subjectData'=>$subjectData,'search'=>$search,'level'=>$level,'title'=>$subjectData?$subjectData['name'].' · Õppetunnid':'Kõik õppetunnid']);
    }
    public static function lesson(): void {
        $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?: 0;$lesson=Lesson::find($id);
        if (!$lesson) {http_response_code(404);render('error',['title'=>'Õppetundi ei leitud','message'=>'See õppematerjal ei ole saadaval.']);return;}
        render('lesson',['lesson'=>$lesson,'comments'=>Comments::byLesson($id),'questionCount'=>count(Quiz::questions($id)),'title'=>$lesson['title']]);
    }
    public static function image(): void {
        $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?: 0;
        $image=Lesson::cover($id);
        if (!$image || empty($image['cover_image']) || !in_array($image['cover_mime'],['image/jpeg','image/png','image/webp','image/gif'],true)) {http_response_code(404);return;}
        header('Content-Type: '.$image['cover_mime']);header('X-Content-Type-Options: nosniff');header('Cache-Control: public, max-age=3600');echo $image['cover_image'];
    }
    public static function quiz(): void {
        $id=(int)($_SERVER['REQUEST_METHOD']==='POST'?($_POST['lesson_id']??0):($_GET['id']??0));
        $lesson=Lesson::find($id);
        if (!$lesson) {http_response_code(404);render('error',['title'=>'Õppetundi ei leitud','message'=>'Test ei ole saadaval.']);return;}
        $questions=Quiz::questions($id);$result=null;$selected=[];
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            if (!csrf_valid()) {http_response_code(400);render('error',['title'=>'Vorm aegus','message'=>'Värskenda lehte ja proovi uuesti.']);return;}
            $selected=Quiz::validAnswers($id,is_array($_POST['answer']??null)?$_POST['answer']:[]);
            $result=quiz_score(Quiz::key($id),$selected);
            if (is_logged_in() && $result['total']>0) Quiz::store((int)$_SESSION['user_id'],$id,$result['correct'],$result['total']);
        }
        render('quiz',['lesson'=>$lesson,'questions'=>$questions,'result'=>$result,'selected'=>$selected,'title'=>'Teadmiste kontroll']);
    }
    public static function comment(): void {
        require_login();
        if ($_SERVER['REQUEST_METHOD']!=='POST'||!csrf_valid()) {http_response_code(400);render('error',['title'=>'Vorm aegus','message'=>'Proovi uuesti.']);return;}
        $id=filter_input(INPUT_POST,'lesson_id',FILTER_VALIDATE_INT) ?: 0;
        $text=trim((string)($_POST['body']??''));
        if (Lesson::find($id) && mb_strlen($text)>0 && mb_strlen($text)<=1200) Comments::add($id,(int)$_SESSION['user_id'],$text);
        redirect('lesson?id='.$id.'#arutelu');
    }
    public static function register(): void {render('register',['title'=>'Loo konto']);}
    public static function registerAnswer(): void {
        if ($_SERVER['REQUEST_METHOD']!=='POST'||!csrf_valid()) {http_response_code(400);render('error',['title'=>'Vorm aegus','message'=>'Värskenda lehte ja proovi uuesti.']);return;}
        $message=Auth::register((string)($_POST['name']??''),(string)($_POST['email']??''),(string)($_POST['password']??''),(string)($_POST['confirm']??''));
        if ($message!==null) {http_response_code(422);render('register',['title'=>'Loo konto','formError'=>$message,'oldName'=>(string)($_POST['name']??''),'oldEmail'=>(string)($_POST['email']??'')]);return;}
        flash('Konto on loodud. Logi sisse, et oma tulemusi salvestada.');redirect('admin/');
    }
    public static function dashboard(): void {
        require_login();$progress=Quiz::progress((int)$_SESSION['user_id']);$history=Quiz::history((int)$_SESSION['user_id']);
        render('dashboard',['progress'=>$progress,'history'=>$history,'title'=>'Minu õpitee']);
    }
}
