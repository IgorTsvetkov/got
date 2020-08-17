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
                <hero-picker-wrapper current_user_id="<?=Yii::$app->user->id?>" game='<?=json_encode($game)?>'>
                <?php if ($game['players'][0]['user']['id'] == Yii::$app->user->id) : ?>
                    <input class="btn btn-primary w-100" type="submit" value="Начать игру">
                <?php endif; ?>
                </hero-picker-wrapper>
        </div>
    </form>
    <a href="/match/left?game_id=<?= $game_id ?>">Покинуть</a>
</div>