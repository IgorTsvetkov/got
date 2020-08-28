<?php

/* @var $this yii\web\View */

use app\models\GameSession;
use yii\helpers\Html;
use yii\helpers\VarDumper;
$this->title = 'Создать игру';
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <form action="/match/start?game_id=<?=$game["id"]?>" method="post">
        <div class="bg-secondary">
                <hero-picker-wrapper current_user_id="<?=Yii::$app->user->id?>" game='<?=json_encode($game)?>'>
                </hero-picker-wrapper>
        </div>
    </form>
</div>