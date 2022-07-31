<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m220605_130231_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => 'LONGTEXT',
            'image' => $this->string(2000),
            'price' => $this->decimal(10,2)->notNull(),
            'status' => $this->tinyInteger(2)->notNull(),
            'created_at' => $this->integer(10),
            'created_by' => $this->integer(10),
            'modified_at' => $this->integer(10),
            'modified_by' => $this->integer(10),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-products-created_by}}',
            '{{%products}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-products-created_by}}',
            '{{%products}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `modified_by`
        $this->createIndex(
            '{{%idx-products-modified_by}}',
            '{{%products}}',
            'modified_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-products-modified_by}}',
            '{{%products}}',
            'modified_by',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-products-created_by}}',
            '{{%products}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-products-created_by}}',
            '{{%products}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-products-modified_by}}',
            '{{%products}}'
        );

        // drops index for column `modified_by`
        $this->dropIndex(
            '{{%idx-products-modified_by}}',
            '{{%products}}'
        );

        $this->dropTable('{{%products}}');
    }
}
