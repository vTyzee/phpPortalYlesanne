<!doctype html>
<html lang="et">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="ÕpiEestis – tasuta õppematerjalid, õppetunnid ja enesekontrollid Eestis õppijatele.">
  <title><?= e(($title ?? 'Avaleht').' · ÕpiEestis') ?></title>
  <link rel="stylesheet" href="<?= e(url('assets/style.css')) ?>">
</head>
<body>
<a class="skip-link" href="#sisu">Liigu sisu juurde</a>
<header class="site-header">
  <div class="site-width header-inner">
    <a href="<?= e(url()) ?>" class="brand" aria-label="ÕpiEestis avaleht"><span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span><span>Õpi<span class="brand-accent">Eestis</span><small>Õppimise portaal</small></span></a>
    <nav class="main-nav" aria-label="Peamenüü"><a href="<?= e(url()) ?>" <?= ($section??'')==='home'?'aria-current="page"':'' ?>>Avaleht</a><a href="<?= e(url('subjects')) ?>" <?= ($section??'')==='subjects'?'aria-current="page"':'' ?>>Õppeained</a><a href="<?= e(url('lessons')) ?>" <?= ($section??'')==='lessons'?'aria-current="page"':'' ?>>Õppetunnid</a></nav>
    <div class="header-actions">
      <?php if (is_logged_in()): ?><a class="header-profile" href="<?= e(url('dashboard')) ?>">Minu õpitee</a><?php if (is_admin()): ?><a class="header-profile" href="<?= e(url('admin/')) ?>">Haldus</a><?php endif; ?><form method="post" action="<?= e(url('admin/logout')) ?>"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button class="btn btn-outline btn-small" type="submit">Välju</button></form>
      <?php else: ?><a class="header-profile" href="<?= e(url('admin/')) ?>">Logi sisse</a><a class="btn btn-primary btn-small" href="<?= e(url('register')) ?>">Loo konto <span aria-hidden="true">↗</span></a><?php endif; ?>
    </div>
  </div>
</header>
<main id="sisu"><?= $content ?></main>
<footer class="site-footer"><div class="site-width footer-inner"><div><a href="<?= e(url()) ?>" class="footer-brand">Õpi<span>Eestis</span></a><p>Õpi omas tempos. Avasta uusi teadmisi.</p></div><div class="footer-links"><a href="<?= e(url('subjects')) ?>">Õppeained</a><a href="<?= e(url('lessons')) ?>">Õppetunnid</a><a href="<?= e(url('register')) ?>">Loo konto</a></div><small>Õppeprojekt · Ei ole Eesti riigi ametlik teenus.</small></div></footer>
</body></html>
