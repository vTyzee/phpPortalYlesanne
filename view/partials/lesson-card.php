<article class="lesson-card">
    <a class="card-cover tone-<?= e($lesson['subject_tone']) ?>" href="<?= e(url('lesson?id=' . (int)$lesson['id'])) ?>" aria-label="Ava õppematerjal: <?= e($lesson['title']) ?>">
        <?php if ($lesson['cover_mime']): ?>
            <img src="<?= e(url('image?id=' . (int)$lesson['id'])) ?>" alt="" loading="lazy">
        <?php else: ?>
            <span class="cover-symbol" aria-hidden="true"><?= e($lesson['subject_icon']) ?></span>
        <?php endif; ?>
    </a>
    <div class="card-body">
        <div class="card-meta"><span><?= e($lesson['subject_name']) ?></span><span aria-hidden="true">·</span><span><?= e($lesson['level']) ?></span><span aria-hidden="true">·</span><span><?= (int)$lesson['duration_min'] ?> min</span></div>
        <h3><a href="<?= e(url('lesson?id=' . (int)$lesson['id'])) ?>"><?= e($lesson['title']) ?></a></h3>
        <p><?= e($lesson['summary']) ?></p>
        <a class="text-link" href="<?= e(url('lesson?id=' . (int)$lesson['id'])) ?>">Loe edasi →</a>
    </div>
</article>
