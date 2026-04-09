<?php

use yii\db\Migration;

class m260409_100100_create_link_visits_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%link_visits}}', [
            'id' => $this->primaryKey(),
            'link_id' => $this->integer()->notNull(),
            'ip_address' => $this->string(45)->null(),
            'user_agent' => $this->text()->null(),
            'referer' => $this->text()->null(),
            'visited_at' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex(
            'idx-link_visits-link_id',
            '{{%link_visits}}',
            'link_id'
        );

        $this->createIndex(
            'idx-link_visits-visited_at',
            '{{%link_visits}}',
            'visited_at'
        );

        $this->addForeignKey(
            'fk-link_visits-link_id-links-id',
            '{{%link_visits}}',
            'link_id',
            '{{%links}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey('fk-link_visits-link_id-links-id', '{{%link_visits}}');
        $this->dropTable('{{%link_visits}}');
    }
}