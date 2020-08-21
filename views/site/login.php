<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Login';
?>
<form action="/site/login" method="POST" class="form-signin">
    <h1 class="h3 mb-3 font-weight-normal text-center">Вход</h1>
    <div>
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
        <label for="inputEmail" class="sr-only">Имя пользователя</label>
        <!-- <input type="email" id="inputEmail" class="form-control" placeholder="Email" required="" autofocus=""> -->
        <input placeholder="<?= $model->getAttributeLabel("username") ?>" value="<?= $model->username ?>" type="text" name="username" class="form-control" autofocus required>
        <? if ($model->errors["username"]) : ?>
            <div class="error">
                <?= Html::error($model, "username") ?>
            </div>
        <? endif; ?>
        <label for="inputPassword" class="sr-only">Пароль</label>
        <input id="inputPassword" type="password" placeholder="<?= $model->getAttributeLabel("password") ?>" value="<?= $model->password ?>" type="text" name="password" class="form-control" autofocus required>
        <? if ($model->errors["password"]) : ?>
            <div class="text-danger">
                <?= Html::error($model, "password") ?>
            </div>
        <? endif; ?>
    </div>
        <div class="checkbox mb-3 d-flex justify-content-center">
            <label>
                <input type="checkbox" value="remember-me"> Запомнить меня
            </label>
        </div>
        <button class="btn btn-md btn-primary btn-block">Войти</button>
        <div class="text-center">
            <a href="/"class="dark-link text-black">Назад</a>
        </div>
</form>