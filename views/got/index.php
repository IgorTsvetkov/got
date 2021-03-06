<?php

use app\assets\AppAsset;
use yii\helpers\VarDumper;

AppAsset::register($this);

// VarDumper::dump($game,10,true);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/web/dist/main.css">
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <div id="app">
        <map-component 
        player_id='<?=$player_id?>' 
        game-string='<?= json_encode($game,JSON_HEX_APOS)?>'
        ></map-component>
    </div>
    <?php $this->endBody() ?>

</body>
<script src="/web/dist/main.js"></script>

</html>
<?php $this->endPage() ?>