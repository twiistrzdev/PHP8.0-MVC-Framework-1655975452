<?php

use app\core\Application;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daishitie</title>
</head>

<body>
    <div>
        <a href="/">Home</a>
        <a href="/about">About</a>
        <a href="/contact">Contact</a>
    </div>

    <?php if (Application::$app->session->getFlash(key: 'success')) : ?>
        <div>
            <?= Application::$app->session->getFlash(key: 'success'); ?>
        </div>
    <?php endif; ?>

    {{content}}
</body>

</html>