<?php

use yii\db\Migration;

/**
 * Class m220724_084851_add_admin_column_to_user
 */
class m220724_084851_add_admin_column_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}','admin',$this->boolean()->after('username'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}','admin');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220724_084851_add_admin_column_to_user cannot be reverted.\n";

        return false;
    }
    */
}
