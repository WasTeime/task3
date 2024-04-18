<?php

use admin\components\PostStatus;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m240416_145936_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $status = new PostStatus();

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'title' => $this->string(255)->notNull(),
            'text' => $this->text()->notNull(),
            'post_category_id' => $this->integer()->null(),
            'status' => $this->integer()->notNull()->defaultValue(key($status->getStatusByName('brandnew'))),
            'image' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-post-category_id',
                'post',
            'post_category_id',
            'post_category',
            'id',
        );

        $this->addForeignKey(
            'fk-post-user_id',
            'post',
            'user_id',
            'user',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-post-category_id', 'post');
        $this->dropForeignKey('fk-post-user_id', 'post');

        $this->dropTable('{{%post}}');
    }
}
