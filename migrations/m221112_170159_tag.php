<?php

use yii\db\Migration;

/**
 * Class m221112_170159_tag
 */
class m221112_170159_tag extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "CREATE TABLE `tag` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `bank_row_id` int NULL,
            `name` varchar(100) NULL
      ) COLLATE 'utf8_general_ci';";

        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221112_170159_tag cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221112_170159_tag cannot be reverted.\n";

        return false;
    }
    */
}
