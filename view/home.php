<?php $section = 'home'; ?>
<section class="np-home-heading site-width">
    <p class="np-kicker">ÕPPEPORTAAL</p>
    <h1>TOP 3 ÕPPEMATERJALID</h1>
    <p>Uued õppematerjalid Eesti õppijatele. Vali õppeaine ja ava endale sobiv tund.</p>
</section>
<div class="site-width np-home-layout">
    <section class="np-home-main" aria-label="Viimased õppematerjalid">
        <?php if ($lessons): ?>
            <div class="lesson-grid news-list">
                <?php foreach ($lessons as $lesson): ?>
                    <?php include __DIR__ . '/partials/lesson-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-state">Õppematerjalid lisatakse peagi.</p>
        <?php endif; ?>
        <a class="btn btn-primary np-more" href="<?= e(url('lessons')) ?>">Kõik õppematerjalid →</a>
    </section>
    <aside class="np-side" aria-label="Õppeained">
        <h2>Õppeained</h2>
        <ul>
            <?php foreach ($subjects as $subject): ?>
                <li><a href="<?= e(url('lessons?subject=' . (int)$subject['id'])) ?>"><?= e($subject['name']) ?><span><?= (int)$subject['lesson_count'] ?></span></a></li>
            <?php endforeach; ?>
        </ul>
        <a class="np-side-all" href="<?= e(url('subjects')) ?>">Vaata kõiki õppeaineid →</a>
        <p class="np-side-note">Õpi omas tempos ja kontrolli teadmisi testi abil.</p>
    </aside>
</div>
