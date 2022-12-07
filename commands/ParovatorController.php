<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use DomDocument;
use SimpleXMLElement;
use app\models\BankRow;
use app\models\Document;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParovatorController extends Controller
{
    /**
     * ["@attributes"]=>
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $q = "SELECT * FROM bank_row";
        $rows = BankRow::findBySql($q)->all();
        foreach ($rows as $row) {
            $q1 = "SELECT * FROM document WHERE total_price = ".$row->amount ;
            // // if additional_info contains string "nay"
            // if (strpos($row->additional_info, 'NAY') !== false) {
            //     echo $q1;
            // }
            
            $documents = Document::findBySql($q1)->all();
            if (count($documents) == 1) {
                echo $row->amount." ".$row->validity_date." ".$row->additional_info."\n";
                echo $documents[0]->organization_name." ".$documents[0]->issued_at.' '."\n";
                echo "\n";
            }
        }

        return ExitCode::OK;
    }

  
}
