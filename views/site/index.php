<?php

use app\assets\AppAsset;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<link rel="stylesheet" href="/web/dist/main.css">

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
                <div class="position-absolute vh-100 vw-100 z-index-10 bg-light d-flex justify-content-center align-items-center opacity-4">
                </div>
                <div class="position-absolute vh-100 vw-100 z-index-10 d-flex flex-column justify-content-center align-items-center">
                    <h1 class="h1 font-weight-light">Monopoly. Game of thrones edition</h1>
                    <button-load id="login" action="/site/login" to="#login-form-wrapper" class="btn h5 btn-primary btn-lg px-5 shadow">Войти</button-load>
                    <button-load id="registration" action="/site/registration" to="#login-form-wrapper" class="h5 lead dark-link text-black">Регистрация</button-load>
                </div>
            </div>
            <div class="cube-wrapper vh-100 vw-100 d-flex justify-content-center align-items-center">
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
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center d-none">
                            <!-- <form-ajax><input type="submit"></form-ajax> -->
                            <form-ajax-wrapper>
                                <div id="login-form-wrapper"></div>
                            </form-ajax-wrapper>
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

    <script src="/web/dist/main.js"></script>
    <script src="/web/js/cube-dice-auth.js"></script>

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>