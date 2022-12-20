<?php

use yii\db\Migration;

/**
 * Class m221217_113912_bmail_doklado_sent
 */
class m221217_113912_bmail_doklado_sent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "ALTER TABLE `bmail`
        ADD `doklado_sent` tinyint(1) NULL";
        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221217_113912_bmail_doklado_sent cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221217_113912_bmail_doklado_sent cannot be reverted.\n";

        return false;
    }
    */
}
