<?php

use yii\db\Migration;

/**
 * Class m221215_200624_bmail_fields2
 */
class m221215_200624_bmail_fields2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "ALTER TABLE `bmail`
        ADD `type` varchar(10) NULL
        ";
        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221215_200624_bmail_fields2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221215_200624_bmail_fields2 cannot be reverted.\n";

        return false;
    }
    */
}
