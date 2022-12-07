<?php

use yii\db\Migration;

/**
 * Class m221127_195259_universal_tag
 */
class m221127_195259_universal_tag extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "ALTER TABLE `tag`
        ADD `payment_id` int NULL";

        \Yii::$app->db->createCommand($q)->execute();
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221127_195259_universal_tag cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221127_195259_universal_tag cannot be reverted.\n";

        return false;
    }
    */
}
