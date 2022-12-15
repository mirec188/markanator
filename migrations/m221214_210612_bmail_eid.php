<?php

use yii\db\Migration;

/**
 * Class m221214_210612_bmail_eid
 */
class m221214_210612_bmail_eid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "ALTER TABLE `bmail`
        ADD `external_id` varchar(200) NULL";

        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221214_210612_bmail_eid cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221214_210612_bmail_eid cannot be reverted.\n";

        return false;
    }
    */
}
