<?php
// The same layout is used for all public, account and admin pages.
// Subject links are shown in the navigation just like categories in Newsportal.
try {
    $navigationSubjects = isset($subjects) && is_array($subjects) ? $subjects : Subject::all();
} catch (PDOException $exception) {
    // Keep the friendly database-error page accessible when MySQL is unavailable.
    $navigationSubjects = [];
}
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ÕpiEestis – õppematerjalid ja teadmiste kontroll Eestis õppijatele.">
    <title><?= e(($title ?? 'Avaleht') . ' · ÕpiEestis') ?></title>
    <link rel="stylesheet" href="<?= e(url('assets/style.css')) ?>">
</head>
<body>
<a class="skip-link" href="#sisu">Liigu sisu juurde</a>
<header class="np-header">
    <div class="site-width np-brandbar">
        <a class="np-brand" href="<?= e(url()) ?>">Õpi<span>Eestis</span></a>
        <span class="np-tagline">Õppematerjalid ja testid Eestis õppijatele</span>
    </div>
    <nav class="np-nav" aria-label="Peamenüü">
        <div class="site-width np-nav-inner">
            <ul class="np-menu">
                <li class="np-dropdown">
                    <details>
                        <summary>Õppeained <span aria-hidden="true">▾</span></summary>
                        <ul class="np-submenu">
                            <li><a href="<?= e(url('subjects')) ?>">Kõik õppeained</a></li>
                            <?php foreach ($navigationSubjects as $subject): ?>
                                <li><a href="<?= e(url('lessons?subject=' . (int)$subject['id'])) ?>"><?= e($subject['name']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </details>
                </li>
                <li><a href="<?= e(url('lessons')) ?>" <?= ($section ?? '') === 'lessons' ? 'aria-current="page"' : '' ?>>Kõik õppematerjalid</a></li>
                <li><a href="<?= e(url()) ?>" <?= ($section ?? '') === 'home' ? 'aria-current="page"' : '' ?>>Avaleht</a></li>
                <?php if (!is_logged_in()): ?>
                    <li><a href="<?= e(url('register')) ?>">Registreeru</a></li>
                    <li><a href="<?= e(url('admin/')) ?>">Logi sisse</a></li>
                <?php else: ?>
                    <li><a href="<?= e(url('dashboard')) ?>">Minu õpitee</a></li>
                    <?php if (is_admin()): ?><li><a href="<?= e(url('admin/')) ?>">Halduspaneel</a></li><?php endif; ?>
                <?php endif; ?>
            </ul>
            <?php if (is_logged_in()): ?>
                <form class="np-logout" method="post" action="<?= e(url('admin/logout')) ?>">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <button type="submit">Välju</button>
                </form>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main id="sisu" class="np-page"><?= $content ?? '' ?></main>
<footer class="np-footer"><div class="site-width">ÕpiEestis · Õppeprojekt, mitte Eesti riigi ametlik teenus.</div></footer>
</body>
</html>
