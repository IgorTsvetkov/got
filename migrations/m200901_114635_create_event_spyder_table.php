<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_spyder}}`.
 */
class m200901_114635_create_event_spyder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->createTable('{{%event_spyder}}', [
            'id' =>$this->primaryKey(),
            'text' =>$this->string(),
            'operation' =>$this->string(),
            'money'=>$this->integer()
        ]);
       $this->batchInsert(
            '{{%event_spyder}}',
            ['text','operation','money'],
            [
                ['От продажи добротного дорнийского вина вы получили 50$','earn',50],
                ['Ваши вложения в бордель окупились. Получить 50$','earn',50],
                ['Переместиться на старт. Забрать деньги 200$','earn',50],
                ['Нападение на Дорнийское судно снабжения завершилось успешно. Взять 200$','earn',200],
                ['Вы попали в плен к Болтонам. С вас содрали кожу.Бросьте кубик и заплатите в 10 раз больше.','rollAndPayMultiply10',null],
                ['Железнорожденные напали на ваш замок.Заплатите предыдущему игроку 50$','payPrevious',50],
                ['Призван королем.Переместиться в Королевски земли','teleportKingLanding',null],
                ['Вы успешно отразили атаку дотракийцев. Получить награду 100$','earn',100],
                ['Ваш города повреждены диким огнем и требует капитального ремонта. Заплатить 40$ за каждый дом и 115$ за каждый постоялый двор','payForRepairHomeAndInn',40],
                ['Железный банк одобрил ваш кредит.Забрать 200$','earn',200],
                ['Вы захватили партирую товара на Золотом тракте. Забрать 150$','earn',150],
                ['Посетить семью Старков. Если вы pass start Забрать 200$','earnIfPassStart',200],
                ['Ланнистеры всегда платят свои долги. Получить 45$ из банка','earn',45],
                //Тюрьма ?? Стена событие

            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('{{%event_spyder}}');
    }
}
