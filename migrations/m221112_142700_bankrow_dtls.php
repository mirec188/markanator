<?php

use yii\db\Migration;

/**
 * Class m221112_142700_bankrow_dtls
 */
class m221112_142700_bankrow_dtls extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $q = "ALTER TABLE `bank_row`
            ADD `additional_info` VARCHAR(500) NULL";

// 		Yii::app()->db->createCommand($q)->execute();
// ALTER TABLE `Order`
// 			CHANGE `company` `company` varchar(200) COLLATE 'utf8_general_ci' NULL AFTER `companyZip`,
// 			ADD `paid` tinyint NULL AFTER `company`,
// 			ADD `paidAmount` decimal NULL AFTER `paid`;

        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221112_142700_bankrow_dtls cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221112_142700_bankrow_dtls cannot be reverted.\n";

        return false;
    }
    */
}
