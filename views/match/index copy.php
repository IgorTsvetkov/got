<?php 

use app\models\GameSession;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;
VarDumper::dump($models[0],10,true);
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
                <?foreach (GameSession::instance()->attributeLabels() as $key=>$label):?>
                <td> <?= $game->getAttribute($key) ?> </td>
                <?endforeach?>
                <td><a href="/match/join?game_id=<?=$game->id?>">Присоединиться</a></td>
            </tr>
            <?endforeach?>
        </tbody>
    </table>
</div>