<?php

use app\models\GameSession;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;
VarDumper::dump($models[0],10,true);
$this->title = 'Список игр';
?>
<h1>Список игр</h1>
<div>
    <game-table></game-table> 
</div>