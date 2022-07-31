<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_addresses}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%orders}}`
 */
class m220605_135838_create_order_addresses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_addresses}}', [
            // 'id' => $this->primaryKey(),
            'order_id' => $this->integer(10)->notNull(),
            'address' => 'LONGTEXT',
            'city' => $this->string(250),
            'state' => $this->string(100),
            'country' => $this->string(100),
            'pincode' => $this->integer(10)->notNull(),
        ]);

         $this->addPrimaryKey('PK_order_addresses', '{{%order_addresses}}', 'order_id');
        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-order_addresses-order_id}}',
            '{{%order_addresses}}',
            'order_id'
        );

        // add foreign key for table `{{%orders}}`
        $this->addForeignKey(
            '{{%fk-order_addresses-order_id}}',
            '{{%order_addresses}}',
            'order_id',
            '{{%orders}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%orders}}`
        $this->dropForeignKey(
            '{{%fk-order_addresses-order_id}}',
            '{{%order_addresses}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-order_addresses-order_id}}',
            '{{%order_addresses}}'
        );

        $this->dropTable('{{%order_addresses}}');
    }
}
