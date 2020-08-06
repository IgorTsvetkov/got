<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%player_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%player}}`
 * - `{{%user}}`
 */
class m200806_112107_create_junction_table_for_player_and_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%player_user}}', [
            'player_id' => $this->integer(),
            'user_id' => $this->integer(),
            'PRIMARY KEY(player_id, user_id)',
        ]);

        // creates index for column `player_id`
        $this->createIndex(
            '{{%idx-player_user-player_id}}',
            '{{%player_user}}',
            'player_id'
        );

        // add foreign key for table `{{%player}}`
        $this->addForeignKey(
            '{{%fk-player_user-player_id}}',
            '{{%player_user}}',
            'player_id',
            '{{%player}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-player_user-user_id}}',
            '{{%player_user}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-player_user-user_id}}',
            '{{%player_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%player}}`
        $this->dropForeignKey(
            '{{%fk-player_user-player_id}}',
            '{{%player_user}}'
        );

        // drops index for column `player_id`
        $this->dropIndex(
            '{{%idx-player_user-player_id}}',
            '{{%player_user}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-player_user-user_id}}',
            '{{%player_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-player_user-user_id}}',
            '{{%player_user}}'
        );

        $this->dropTable('{{%player_user}}');
    }
}
