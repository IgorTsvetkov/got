<?php

use app\models\GameSession;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

$this->title = 'Список игр';
?>
<h1>Список игр</h1>
<a href="/match/create-lobby">Создать игровое лобби</a>
<div>
    <table class="table">
        <thead>
            <tr>
                <?foreach (GameSession::instance()->attributeLabels() as $label):?>
                <th scope="col"><?= $label ?></th>
                <?endforeach?>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?foreach ($games as $game):?>
            <tr>
                <?foreach ($game->getAttributes() as $attr):?>
                <td> <?= $attr ?> </td>
                <?endforeach?>
                <td><a href="/match/join?game_id=<?=$game->id?>">Присоединиться</a></td>
            </tr>
            <?endforeach?>
        </tbody>
    </table>
</div>