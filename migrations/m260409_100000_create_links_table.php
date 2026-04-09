<?php

use yii\db\Migration;

class m260409_100000_create_links_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%links}}', [
            'id' => $this->primaryKey(),
            'original_url' => $this->text()->notNull(),
            'short_code' => $this->string(32)->notNull(),
            'click_count' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex(
            'idx-links-short_code-unique',
            '{{%links}}',
            'short_code',
            true
        );
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%links}}');
    }
}