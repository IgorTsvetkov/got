<?php

/* @var $this yii\web\View */

use app\models\GameSession;
use yii\helpers\Html;
use yii\helpers\VarDumper;
VarDumper::dump($users,10,true);

$this->title = 'Создать игру';
?>
<div >
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?=GameSession::instance()->getAttributeLabel("id")?>:<?=$game_id?></h2>
    <form action="/got/create" method="post">
        <hero-picker-component users_string='<?=$users?>'>

        </hero-picker-component>
        <?php if($model->owner):?>
        <?php endif;?>
        <input type="submit" value="Начать игру">
    </form>
    <a href="/match/left?game_id=<?=$game_id?>">Покинуть</a>
</div>
