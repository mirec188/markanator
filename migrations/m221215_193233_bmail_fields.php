<?php

use yii\db\Migration;

/**
 * Class m221215_193233_bmail_fields
 */
class m221215_193233_bmail_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $q = "ALTER TABLE `bmail`
        ADD `transaction_amount` decimal(12,2) NULL,
        ADD `ledger_balance` decimal(12,2) NULL,
        ADD `actual_balance` decimal(12,2) NULL,
        ADD `dispo_balance` decimal(12,2) NULL,
        ADD `processed_at` datetime null,
        ADD `trx_description` varchar(255) NULL
        ";
        \Yii::$app->db->createCommand($q)->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221215_193233_bmail_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221215_193233_bmail_fields cannot be reverted.\n";

        return false;
    }
    */
}
