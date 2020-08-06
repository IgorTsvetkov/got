<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%player_game_session}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%player}}`
 * - `{{%game_session}}`
 */
class m200806_112331_create_junction_table_for_player_and_game_session_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%player_game_session}}', [
            'player_id' => $this->integer(),
            'game_session_id' => $this->integer(),
            'PRIMARY KEY(player_id, game_session_id)',
        ]);

        // creates index for column `player_id`
        $this->createIndex(
            '{{%idx-player_game_session-player_id}}',
            '{{%player_game_session}}',
            'player_id'
        );

        // add foreign key for table `{{%player}}`
        $this->addForeignKey(
            '{{%fk-player_game_session-player_id}}',
            '{{%player_game_session}}',
            'player_id',
            '{{%player}}',
            'id',
            'CASCADE'
        );

        // creates index for column `game_session_id`
        $this->createIndex(
            '{{%idx-player_game_session-game_session_id}}',
            '{{%player_game_session}}',
            'game_session_id'
        );

        // add foreign key for table `{{%game_session}}`
        $this->addForeignKey(
            '{{%fk-player_game_session-game_session_id}}',
            '{{%player_game_session}}',
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
        // drops foreign key for table `{{%player}}`
        $this->dropForeignKey(
            '{{%fk-player_game_session-player_id}}',
            '{{%player_game_session}}'
        );

        // drops index for column `player_id`
        $this->dropIndex(
            '{{%idx-player_game_session-player_id}}',
            '{{%player_game_session}}'
        );

        // drops foreign key for table `{{%game_session}}`
        $this->dropForeignKey(
            '{{%fk-player_game_session-game_session_id}}',
            '{{%player_game_session}}'
        );

        // drops index for column `game_session_id`
        $this->dropIndex(
            '{{%idx-player_game_session-game_session_id}}',
            '{{%player_game_session}}'
        );

        $this->dropTable('{{%player_game_session}}');
    }
}
