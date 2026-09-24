<?php
declare(strict_types=1);
class Comments {
    public static function byLesson(int $id): array {
        $q=db()->prepare('SELECT c.body,c.created_at,u.username FROM lesson_comments c JOIN users u ON u.id=c.user_id WHERE c.lesson_id=? ORDER BY c.id DESC');$q->execute([$id]);return $q->fetchAll();
    }
    public static function add(int $lessonId,int $userId,string $body): void {
        $q=db()->prepare('INSERT INTO lesson_comments (lesson_id,user_id,body) VALUES (?,?,?)');$q->execute([$lessonId,$userId,$body]);
    }
}
