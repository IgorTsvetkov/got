<?php

/* @var $this yii\web\View */

use app\models\GameSession;
use yii\helpers\Html;
use yii\helpers\VarDumper;


$this->title = 'Создать игру';
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= GameSession::instance()->getAttributeLabel("id") ?>:<?= $game["id"] ?></h2>
    <form action="/got/create" method="post">
        <div class="bg-secondary">
            <div class="flex flex-row justify-content-center align-items-center flex-wrap text-center lead">
                <hero-picker-wrapper game='<?=json_encode($game)?>'>

                </hero-picker-wrapper>
            </div>
            
        </div>


        </hero-picker-component>
        <?php if ($owner_id === Yii::$app->user->id) : ?>
            <input type="submit" value="Начать игру">
        <?php endif; ?>
    </form>
    <a href="/match/left?game_id=<?= $game_id ?>">Покинуть</a>
</div>