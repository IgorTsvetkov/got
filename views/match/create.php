<?php

/* @var $this yii\web\View */

use app\models\GameSession;
use yii\helpers\Html;
use yii\helpers\VarDumper;

VarDumper::dump($users, 10, true);
VarDumper::dump($users, 10, true);

$this->title = 'Создать игру';
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= GameSession::instance()->getAttributeLabel("id") ?>:<?= $game_id ?></h2>
    <form action="/got/create" method="post">
        <div class="bg-secondary">
            <div class="flex flex-row justify-content-center align-items-center flex-wrap text-center lead">
                <hero-picker-wrapper>
                    <?for($i=0,$i<6;$i++):?>
                        <hero-picker-component 
                        is_owner="<?=$owner_id==$users[$i]->id?>" 
                        username='<?= $users[$i]->username ?>'
                        >
                    <?endforeach;?>
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