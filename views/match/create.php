<?php

/* @var $this yii\web\View */

use app\models\GameSession;
use yii\helpers\Html;
use yii\helpers\VarDumper;
$this->title = 'Создать игру';
// VarDumper::dump($game,10,true);
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= GameSession::instance()->getAttributeLabel("id") ?>:<?= $game["id"] ?></h2>
    <form action="/match/start?game_id=<?=$game["id"]?>" method="post">
        <div class="bg-secondary">
                <hero-picker-wrapper current_user_id="<?=Yii::$app->user->id?>" game='<?=json_encode($game)?>'>
                <?php if ($game['leader_user_id'] == Yii::$app->user->id) : ?>
                    <start-game-button game_id="<?=$game->id?>" action="/match/start?game_id=<?=$game["id"]?>">Начать игру</start-game-button>
                <?php endif; ?>
                </hero-picker-wrapper>
        </div>
    </form>
    <a href="/match/leave?game_id=<?= $game["id"] ?>">Покинуть</a>
</div>