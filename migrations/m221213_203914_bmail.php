<?php

use yii\db\Migration;

/**
 * Class m221213_203914_bmail
 */
class m221213_203914_bmail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "CREATE TABLE `bmail` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `subject` varchar(255) NULL,
            `sender` varchar(255) NULL,
            `receiver` varchar(255) NULL,
            `date` datetime nulL,
            `body` text NULL
        ) COLLATE 'utf8_general_ci';";

        \Yii::$app->db->createCommand($q)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221213_203914_bmail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221213_203914_bmail cannot be reverted.\n";

        return false;
    }
    */
}
