<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_spider}}`.
 */
class m200901_114635_create_event_spider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_spider}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'action_name' => $this->string(),
        ]);
        $this->batchInsert(
            '{{%event_spider}}',
            ['text'],
            [
                ["От продажи добротного дорнийского вина вы получили 50 _money_sign_","takeMoney"],
                ["Ваши вложения в бордель окупились. Получить 50 _money_sign_","takeMoney"],
                ["Переместиться на старт. Забрать деньги 200 _money_sign_","takeMoney"],
                ["Нападение на Дорнийское судно снабжения завершилось успешно. Взять 200 _money_sign_","takeMoney"],
                ["Вы попали в плен к Болтонам. С вас содрали кожу.Бросьте кубик и заплатите в 10 раз больше.","takeMoney"],
                ["Железнорожденный напал на ваш замок.Заплатите предыдущему игроку 50 _money_sign_","takeMoney"],
                ["Призван королем.Переместиться в Королевски земли","takeMoney"],
                ["Вы успешно отразили атаку дотракийцев. Получить награду 100  _money_sign_","takeMoney"],
                ["Ваш города повреждены диким огнем и требует капитального ремонта. Заплатить 40 _money_sign_ за каждый дом и 115 _money_sign_ за каждый постоялый двор","payForEveryHomeAndInn"],
                ["Железный банк одобрил ваш кредит.Забрать 200 _money_sign_","payForEveryHomeAndInn"],
                ["Вы захватили партирую товара на Золотом тракте. Забрать 150 _money_sign_","payForEveryHomeAndInn"],
                ["Посетить семью Старков. Если вы pass start Забрать 200 _money_sign_","payForEveryHomeAndInn"],
                ["Ланнистеры всегда платят долги. Взять 45 _money_sign_ из банка","payForEveryHomeAndInn"],
                //Тюрьма ?? Стена событие

            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event_spider}}');
    }
}
