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
        ]);
        $this->batchInsert(
            '{{%event_spider}}',
            ['text'],
            [
                ["Вы успешно отразили нападение дотракийцев. Получить награду 100 _money_sign_"],
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
