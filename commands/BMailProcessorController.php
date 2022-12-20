<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BMailProcessorController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $override = false;
        if (!$override) {
            $and = "AND processed_at IS NULL";
        }
        $q="SELECT * FROM bmail WHERE transaction_amount is not null $and ORDER BY id desc";
        $bmails = \Yii::$app->db->createCommand($q)->queryAll();
        foreach ($bmails as $bmail) {
            $this->process($bmail);
        }


        return ExitCode::OK;
    }

    private function process($bmail) {
        $bankRow = \app\models\BankRow::find()->where(['bmail_id' => $bmail['id']])->one();
        
        if (!$bankRow) {
            $bankRow = new \app\models\BankRow();
        }
        
        $bankRow->amount=$bmail['transaction_amount'];
        $bankRow->type = $bmail['type'];
        $bankRow->booking_date=$bmail['date'];
        $bankRow->validity_date=$bmail['date'];
        $bankRow->additional_info = $bmail['trx_description'];
        $bankRow->bmail_id = $bmail['id'];
        $saved = $bankRow->save(false);

        if ($saved) {
            $bmailModel = \app\models\BMail::find()->where(['id' => $bmail['id']])->one();
            $bmailModel->processed_at = date('Y-m-d H:i:s');
            $bmailSaved = $bmailModel->save(false);
        }

        return $saved && $bmailSaved;
    }
}