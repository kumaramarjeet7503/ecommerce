<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cart_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%user}}`
 */
class m220605_135655_create_cart_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cart_items}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(10)->notNull(),
            'quantity' => $this->integer(2)->notNull(),
            'user_id' => $this->integer(10)->notNull(),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-cart_items-product_id}}',
            '{{%cart_items}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-cart_items-product_id}}',
            '{{%cart_items}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-cart_items-user_id}}',
            '{{%cart_items}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-cart_items-user_id}}',
            '{{%cart_items}}',
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
        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-cart_items-product_id}}',
            '{{%cart_items}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-cart_items-product_id}}',
            '{{%cart_items}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-cart_items-user_id}}',
            '{{%cart_items}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-cart_items-user_id}}',
            '{{%cart_items}}'
        );

        $this->dropTable('{{%cart_items}}');
    }
}
