<?php

/* @var $this yii\web\View */

use app\models\GameSession;
use yii\helpers\Html;
use yii\helpers\VarDumper;
VarDumper::dump($usernames,10,true);

$this->title = 'Создать игру';
?>
<div >
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?=GameSession::instance()->getAttributeLabel("id")?>:<?=$session_id?></h2>
    <form class="f"action="/got/create" method="post">
        <hero-picker-component usernames='<?=$usernames?>'>

        </hero-picker-component>
        <?php if($model->owner):?>
        <?php endif;?>
        <input type="submit" value="Начать игру">
    </form>
    <
</div>
