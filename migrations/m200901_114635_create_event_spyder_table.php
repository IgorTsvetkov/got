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
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'operation' => $this->string(),
            'money'=>$this->integer()
        ]);
        $this->batchInsert(
            '{{%event_spyder}}',
            ['text'],
            [
                ["От продажи добротного дорнийского вина вы получили 50 _money_sign_","earn",50],
                ["Ваши вложения в бордель окупились. Получить 50 _money_sign_","earn",50],
                ["Переместиться на старт. Забрать деньги 200 _money_sign_","earn",50],
                ["Нападение на Дорнийское судно снабжения завершилось успешно. Взять 200 _money_sign_","earn",200],
                ["Вы попали в плен к Болтонам. С вас содрали кожу.Бросьте кубик и заплатите в 10 раз больше.","rollAndPayMultiply10"],
                ["Железнорожденные напали на ваш замок.Заплатите предыдущему игроку 50 _money_sign_","payPrevious",50],
                ["Призван королем.Переместиться в Королевски земли","teleportKingLanding",null],
                ["Вы успешно отразили атаку дотракийцев. Получить награду 100_money_sign_","earn",100],
                ["Ваш города повреждены диким огнем и требует капитального ремонта. Заплатить 40 _money_sign_ за каждый дом и 115 _money_sign_ за каждый постоялый двор","payForRepairHomeAndInn"],
                ["Железный банк одобрил ваш кредит.Забрать 200 _money_sign_","earn",200],
                ["Вы захватили партирую товара на Золотом тракте. Забрать 150 _money_sign_","earn",150],
                ["Посетить семью Старков. Если вы pass start Забрать 200 _money_sign_","earnIfPassStart",200],
                ["Ланнистеры всегда платят свои долги. Получить 45 _money_sign_ из банка","earn",45],
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
