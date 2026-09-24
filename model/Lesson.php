<?php
declare(strict_types=1);
class Lesson {
    private const FIELDS = 'l.id,l.subject_id,l.title,l.summary,l.body,l.level,l.duration_min,l.cover_mime,l.published,l.created_at,s.name AS subject_name,s.icon AS subject_icon,s.tone AS subject_tone';
    public static function latest(int $limit=6): array {
        $q=db()->prepare('SELECT '.self::FIELDS.' FROM lessons l JOIN subjects s ON s.id=l.subject_id WHERE l.published=1 ORDER BY l.id DESC LIMIT ?');
        $q->bindValue(1,$limit,PDO::PARAM_INT); $q->execute(); return $q->fetchAll();
    }
    public static function list(?int $subject=null, string $search='', string $level=''): array {
        $sql='SELECT '.self::FIELDS.' FROM lessons l JOIN subjects s ON s.id=l.subject_id WHERE l.published=1'; $params=[];
        if ($subject !== null) { $sql.=' AND l.subject_id=?'; $params[]=$subject; }
        if ($search !== '') { $sql.=' AND (l.title LIKE ? OR l.summary LIKE ? OR l.body LIKE ?)'; $term='%'.$search.'%'; array_push($params,$term,$term,$term); }
        if (in_array($level,['Algaja','Kesktase','Edasijõudnu'],true)) { $sql.=' AND l.level=?'; $params[]=$level; }
        $sql.=' ORDER BY l.id DESC'; $q=db()->prepare($sql); $q->execute($params); return $q->fetchAll();
    }
    public static function find(int $id, bool $publishedOnly=true): ?array {
        $sql='SELECT '.self::FIELDS.' FROM lessons l JOIN subjects s ON s.id=l.subject_id WHERE l.id=?'.($publishedOnly?' AND l.published=1':'');
        $q=db()->prepare($sql);$q->execute([$id]);return $q->fetch() ?: null;
    }
    public static function count(): int { return (int)db()->query('SELECT COUNT(*) FROM lessons WHERE published=1')->fetchColumn(); }
    public static function cover(int $id): ?array {
        $q=db()->prepare('SELECT cover_image,cover_mime FROM lessons WHERE id=? AND published=1'); $q->execute([$id]);return $q->fetch() ?: null;
    }
    public static function allForAdmin(): array {
        return db()->query('SELECT l.*,s.name AS subject_name FROM lessons l JOIN subjects s ON s.id=l.subject_id ORDER BY l.id DESC')->fetchAll();
    }
}
