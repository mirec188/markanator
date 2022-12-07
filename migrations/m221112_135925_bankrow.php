<?php

use yii\db\Migration;

/**
 * Class m221112_135925_bankrow
 */
class m221112_135925_bankrow extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "CREATE TABLE `bank_row` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `amount` decimal(12,2) NOT NULL,
            `type` varchar(100) NULL,
            `booking_date` date NULL,
            `validity_date` date NULL
      ) COLLATE 'utf8_general_ci';";

        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221112_135925_bankrow cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221112_135925_bankrow cannot be reverted.\n";

        return false;
    }
    */
}
