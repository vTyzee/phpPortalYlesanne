<?php
declare(strict_types=1);
class Quiz {
    public static function questions(int $lessonId): array {
        $q=db()->prepare('SELECT id,question FROM quiz_questions WHERE lesson_id=? ORDER BY id');$q->execute([$lessonId]);
        $questions=$q->fetchAll();
        $opts=db()->prepare('SELECT id,option_text FROM quiz_options WHERE question_id=? ORDER BY id');
        foreach ($questions as &$question) { $opts->execute([$question['id']]);$question['options']=$opts->fetchAll(); } unset($question);
        return $questions;
    }
    public static function key(int $lessonId): array {
        $q=db()->prepare('SELECT q.id, o.id AS correct_option_id FROM quiz_questions q JOIN quiz_options o ON o.question_id=q.id AND o.is_correct=1 WHERE q.lesson_id=? ORDER BY q.id');
        $q->execute([$lessonId]);return $q->fetchAll();
    }
    public static function validAnswers(int $lessonId, array $raw): array {
        $options=[]; foreach (self::questions($lessonId) as $q) $options[(int)$q['id']]=array_map('intval',array_column($q['options'],'id'));
        $out=[]; foreach ($raw as $qid=>$option) {
            if (!is_scalar($option) || !ctype_digit((string)$qid) || !ctype_digit((string)$option)) continue;
            $qid=(int)$qid;$option=(int)$option;
            if (isset($options[$qid]) && in_array($option,$options[$qid],true)) $out[$qid]=$option;
        }
        return $out;
    }
    public static function store(int $userId,int $lessonId,int $correct,int $total): void {
        $q=db()->prepare('INSERT INTO quiz_attempts (user_id,lesson_id,correct_count,total_count) VALUES (?,?,?,?)');$q->execute([$userId,$lessonId,$correct,$total]);
    }
    public static function progress(int $userId): array {
        $q=db()->prepare('SELECT COUNT(DISTINCT lesson_id) AS attempted,COUNT(DISTINCT CASE WHEN correct_count*100>=total_count*70 THEN lesson_id END) AS completed,COUNT(*) AS attempts FROM quiz_attempts WHERE user_id=?');
        $q->execute([$userId]);return $q->fetch() ?: ['attempted'=>0,'completed'=>0,'attempts'=>0];
    }
    public static function history(int $userId): array {
        $q=db()->prepare('SELECT a.*,l.title FROM quiz_attempts a JOIN lessons l ON l.id=a.lesson_id WHERE a.user_id=? ORDER BY a.id DESC LIMIT 8');$q->execute([$userId]);return $q->fetchAll();
    }
}
