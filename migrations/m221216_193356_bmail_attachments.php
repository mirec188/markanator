<?php

use yii\db\Migration;

/**
 * Class m221216_193356_bmail_attachments
 */
class m221216_193356_bmail_attachments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "CREATE TABLE `bmail_attachment` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `bmail_id` int(11) NOT NULL,
            `filename` varchar(255) NOT NULL,
            `content` longblob NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        \Yii::$app->db->createCommand($q)->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221216_193356_bmail_attachments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221216_193356_bmail_attachments cannot be reverted.\n";

        return false;
    }
    */
}
