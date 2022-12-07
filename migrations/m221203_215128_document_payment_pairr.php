<?php

use yii\db\Migration;

/**
 * Class m221203_215128_document_payment_pairr
 */
class m221203_215128_document_payment_pairr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "ALTER TABLE `bank_row`
        ADD `document_id` varchar(255) NULL";

        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221203_215128_document_payment_pairr cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221203_215128_document_payment_pairr cannot be reverted.\n";

        return false;
    }
    */
}
