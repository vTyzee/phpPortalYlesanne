<?php
declare(strict_types=1);

/** Isiklikud salvestatud õppematerjalid. Kasutaja ID tuleb sisselogitud sessioonist. */
class Bookmark {
    public static function has(int $userId, int $lessonId): bool {
        $q = db()->prepare('SELECT 1 FROM lesson_bookmarks WHERE user_id = ? AND lesson_id = ? LIMIT 1');
        $q->execute([$userId, $lessonId]);
        return (bool)$q->fetchColumn();
    }

    public static function toggle(int $userId, int $lessonId): bool {
        $pdo = db();
        // UNIQUE(user_id,lesson_id) väldib sama materjali kahekordset salvestamist.
        if (self::has($userId, $lessonId)) {
            $q = $pdo->prepare('DELETE FROM lesson_bookmarks WHERE user_id = ? AND lesson_id = ?');
            $q->execute([$userId, $lessonId]);
            return false;
        }
        $q = $pdo->prepare('INSERT IGNORE INTO lesson_bookmarks(user_id,lesson_id) VALUES (?,?)');
        $q->execute([$userId, $lessonId]);
        return true;
    }

    public static function forUser(int $userId): array {
        $q = db()->prepare('SELECT l.id,l.title,s.name AS subject_name FROM lesson_bookmarks b JOIN lessons l ON l.id=b.lesson_id JOIN subjects s ON s.id=l.subject_id WHERE b.user_id=? AND l.published=1 ORDER BY b.created_at DESC LIMIT 20');
        $q->execute([$userId]);
        return $q->fetchAll();
    }
}
