<?php

use yii\db\Migration;

/**
 * Class m221215_202329_bmail_id
 */
class m221215_202329_bmail_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "ALTER TABLE `bank_row`
        ADD `bmail_id` int(11) NULL";
        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221215_202329_bmail_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221215_202329_bmail_id cannot be reverted.\n";

        return false;
    }
    */
}
