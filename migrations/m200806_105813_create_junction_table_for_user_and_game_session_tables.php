<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_game_session}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%game_session}}`
 */
class m200806_105813_create_junction_table_for_user_and_game_session_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_game_session}}', [
            'user_id' => $this->integer(),
            'game_session_id' => $this->integer(),
            'PRIMARY KEY(user_id, game_session_id)',
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_game_session-user_id}}',
            '{{%user_game_session}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_game_session-user_id}}',
            '{{%user_game_session}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `game_session_id`
        $this->createIndex(
            '{{%idx-user_game_session-game_session_id}}',
            '{{%user_game_session}}',
            'game_session_id'
        );

        // add foreign key for table `{{%game_session}}`
        $this->addForeignKey(
            '{{%fk-user_game_session-game_session_id}}',
            '{{%user_game_session}}',
            'game_session_id',
            '{{%game_session}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_game_session-user_id}}',
            '{{%user_game_session}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_game_session-user_id}}',
            '{{%user_game_session}}'
        );

        // drops foreign key for table `{{%game_session}}`
        $this->dropForeignKey(
            '{{%fk-user_game_session-game_session_id}}',
            '{{%user_game_session}}'
        );

        // drops index for column `game_session_id`
        $this->dropIndex(
            '{{%idx-user_game_session-game_session_id}}',
            '{{%user_game_session}}'
        );

        $this->dropTable('{{%user_game_session}}');
    }
}
