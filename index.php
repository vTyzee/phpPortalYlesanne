<?php
declare(strict_types=1);
require __DIR__.'/inc/bootstrap.php';
require __DIR__.'/inc/db.php';
foreach (['Subject','Lesson','Quiz','Comments','Auth','Bookmark'] as $model) require __DIR__.'/model/'.$model.'.php';
require __DIR__.'/controller/Controller.php';
require __DIR__.'/route/routing.php';
