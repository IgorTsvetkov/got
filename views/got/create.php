<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Создать игру';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <form class="f"action="/got/create" method="post">
        <hero-picker-component name="owner_1">

        </hero-picker-component>
        <?php if($model->owner):?>
        <?php endif;?>
    </form>
</div>
