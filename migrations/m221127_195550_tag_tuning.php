<?php

use yii\db\Migration;

/**
 * Class m221127_195550_tag_tuning
 */
class m221127_195550_tag_tuning extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "ALTER TABLE `tag`
        ADD `document_id` int NULL";

        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221127_195550_tag_tuning cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221127_195550_tag_tuning cannot be reverted.\n";

        return false;
    }
    */
}
