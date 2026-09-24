<?php
declare(strict_types=1);
class Subject {
    public static function all(): array { return db()->query('SELECT s.*, (SELECT COUNT(*) FROM lessons l WHERE l.subject_id=s.id AND l.published=1) AS lesson_count FROM subjects s ORDER BY s.sort_order,s.id')->fetchAll(); }
    public static function find(int $id): ?array { $q=db()->prepare('SELECT * FROM subjects WHERE id=?'); $q->execute([$id]); return $q->fetch() ?: null; }
}
