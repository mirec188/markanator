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
class BMailParserController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {

        $q="SELECT * FROM bmail ORDER BY id desc";
        $bmails = \Yii::$app->db->createCommand($q)->queryAll();
        foreach ($bmails as $bmail) {
            $this->parseBMail($bmail);
        }

        return ExitCode::OK;
    }

    private function parseBMail($bmail)
    {
        if ($bmail['sender'] == 'b-mail@tatrabanka.sk') {
            $data = str_replace(',', '', $bmail['body']);

            // data is 9734.45 EUR 
            // make a regex to extract the number with dot as decimal separator
            $pattern = "/\b\d+\.\d+\b/";
            
            preg_match_all($pattern, $data, $matches);
            
            // The number will be in the first element of the matches array

            // Print the matches

            if (count($matches[0]) == 4) {
                $transactionAmount = $matches[0][0];
                $ledgerBalance = $matches[0][1];
                $actualBalance = $matches[0][2];
                $dispoBalance = $matches[0][3];
                echo "Transaction amount: $transactionAmount";
                echo "Ledger balance: $ledgerBalance";
                echo "Actual balance: $actualBalance";
                echo "Dispo balance: $dispoBalance";

                // find string 'decreased' 
                preg_match('/decreased/', $data, $match);
                if (isset($match[0])) {
                    $decreased = true;
                } else {
                    if (preg_match('/increased/', $data, $matchi)) {
                        $decreased = false;
                    }
                }
                
                preg_match('/Transaction description: ([^\n\r]*)/', $data, $matchDescription);
                if (isset($matchDescription[1])) {
                    $description = $matchDescription[1];
                }

                if (isset($decreased) && isset($description)) {
                    $this->updateBmail($bmail, $transactionAmount, $ledgerBalance, $actualBalance, $dispoBalance, $decreased, $description);
                }
            } 

            // print_r($matches);

            
        }
    }

    private function updateBmail($bmail, $transactionAmount, $ledgerBalance, $actualBalance, $dispoBalance, $decreased,  $description)
    {
        if ($decreased) {
            $type = 'DBIT';
        } else {
            $type = 'CRDT';
        }
        $q = "UPDATE bmail SET transaction_amount = :transaction_amount, ledger_balance = :ledger_balance, actual_balance = :actual_balance, dispo_balance = :dispo_balance, type = :type, trx_description = :trx_description
                    WHERE id = :id";
        $params = [
            ':transaction_amount' => $transactionAmount,
            ':ledger_balance' => $ledgerBalance,
            ':actual_balance' => $actualBalance,
            ':dispo_balance' => $dispoBalance,
            ':trx_description' => $description,
            ':type' => $type,
            ':id' => $bmail['id']
        ];
        \Yii::$app->db->createCommand($q)->bindValues($params)->execute();
    }
}
