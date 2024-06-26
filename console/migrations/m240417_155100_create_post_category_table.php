<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post-category}}`.
 */
class m240417_155100_create_post_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post-category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post-category}}');
    }
}
