<?php

use yii\db\Migration;

/**
 * Class m221112_152154_bankrow_id
 */
class m221112_152154_bankrow_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "ALTER TABLE `bank_row`
        ADD `external_id` VARCHAR(500) NULL";

    \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221112_152154_bankrow_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221112_152154_bankrow_id cannot be reverted.\n";

        return false;
    }
    */
}
