<?php

use app\assets\AppAsset;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="/web/js/main.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/web/css/cube-dice.css">
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <div id="app">
    <div class="position-relative">
        <div class="auth-front">
            <div class=" position-absolute vh-100 vw-100 z-index-10 bg-light d-flex justify-content-center align-items-center opacity-4">
            </div>
            <div class="position-absolute vh-100 vw-100 z-index-10 d-flex flex-column    justify-content-center align-items-center">
                <h1 class="h1 font-weight-light">Monopoly. Game of thrones edition</h1>
                <div id="login" class="btn h5 btn-primary btn-lg px-5 shadow">Войти</div>
                <a class="h5 lead dark-link text-black">Регистрация</a>
            </div>
        </div>
        <div class="cube-wrapper bg-primary vh-100 vw-100 d-flex justify-content-center align-items-center">
            <div class="cube default-animation position-relative">
                <div class="front square border border-secondary shadow d-flex justify-content-center align-items-center">
                    <div class="point bg-secondary"></div>
                </div>
                <div class="back square border border-secondary shadow d-flex justify-content-center align-items-center">
                    <div class="auth-front d-flex w-75 h-75 flex-column align-items-center justify-content-between">
                        <div class="d-flex w-100 justify-content-between ">
                            <div class="point bg-secondary"></div>
                            <div class="point bg-secondary"></div>
                        </div>
                        <div class="d-flex w-100 justify-content-between">
                            <div class="point bg-secondary"></div>
                            <div class="point bg-secondary"></div>
                        </div>
                        <div class="d-flex w-100 justify-content-between ">
                            <div class="point bg-secondary"></div>
                            <div class="point bg-secondary"></div>
                        </div>
                    </div>
                    <div id="login-form-wrapper" class="w-100 h-100 d-flex align-items-center justify-content-center d-none">
                        <form-ajax><input type="submit"></form-ajax>
                        <form class="form-signin">
                            <h1 class="h3 mb-3 font-weight-normal text-center">Вход</h1>
                            <div>
                                        <input type="hidden"
                            name="<?=Yii::$app->request->csrfParam?>"
                            value="<?=Yii::$app->request->csrfToken?>">
                            <label for="inputEmail" class="sr-only">Username</label>
                            <!-- <input type="email" id="inputEmail" class="form-control" placeholder="Email" required="" autofocus=""> -->
                            <input placeholder="<?= $model->getAttributeLabel("username") ?>" 
                            value="<?=$model->username?>"
                            type="text" name="username" class="form-control" autofocus>
                            <?if($model->errors["username"]):?>
                                <div class="error">
                                    <?= Html::error($model, "username") ?>
                                </div>
                            <?endif;?>
                            
                            <label for="inputPassword" class="sr-only">Password</label>
                            <input type="password" id="inputPassword" class="form-control" placeholder="Пароль" required="">
                            <div class="checkbox mb-3 d-flex justify-content-center">
                                <label>
                                    <input type="checkbox" value="remember-me"> Запомнить меня
                                </label>
                            </div>
                            <button class="btn btn-md btn-primary btn-block" type="submit">Войти</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="left square border border-secondary shadow d-flex justify-content-center align-items-center">
                    <div class="d-flex w-75 h-75 flex-column align-items-center justify-content-between">
                        <div class="d-flex w-100 justify-content-between ">
                            <div class="point bg-secondary"></div>
                            <div class="point bg-secondary"></div>
                        </div>
                        <div class="d-flex w-100 justify-content-center ">
                            <div class="point bg-secondary"></div>
                        </div>
                        <div class="d-flex w-100 justify-content-between ">
                            <div class="point bg-secondary"></div>
                            <div class="point bg-secondary"></div>
                        </div>
                    </div>
                </div>
                <div class="right square border border-secondary shadow d-flex justify-content-center align-items-center">
                    <div class="w-50 h-50 d-flex flex-column justify-content-between">
                        <div class="align-self-end">
                            <div class="point bg-secondary"></div>
                        </div>
                        <div>
                            <div class="point bg-secondary"></div>
                        </div>
                    </div>
                </div>
                <div class=" square top border border-secondary shadow d-flex justify-content-center align-items-center">
                    <div class="w-50 h-50 d-flex flex-column justify-content-between">
                        <div class="d-flex w-100 align-self-end justify-content-between">
                            <div class="point bg-secondary"></div>
                            <div class="point bg-secondary"></div>
                        </div>
                        <div class="d-flex w-100 justify-content-between">
                            <div class="point bg-secondary"></div>
                            <div class="point bg-secondary"></div>
                        </div>
                    </div>
                </div>
                <div class=" square bottom border border-secondary shadow d-flex justify-content-center align-items-center">
                    <div class="d-flex w-75 h-75 flex-column align-items-center justify-content-between">
                        <div class="d-flex w-100 justify-content-start ">
                            <div class="point bg-secondary"></div>
                        </div>
                        <div class="d-flex w-100 justify-content-center">
                            <div class="point bg-secondary"></div>
                        </div>
                        <div class="d-flex w-100 justify-content-end ">
                            <div class="point bg-secondary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <script src="/web/js/main.js"></script>
    <script src="/web/js/cube-dice-auth.js"></script>

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>