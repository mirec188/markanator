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

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CmtController extends Controller
{
    /**
     * ["@attributes"]=>
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($file)
    {
        $dom = $this->readFile($file);
        $rows = [];
        foreach ($dom->BkToCstmrStmt->Stmt[0] as $key => $ntry) {
            


            if ($key == "Ntry") {
                $row = BankRow::findOne(["external_id"=>$ntry->NtryRef]);
                if (!$row) {
                    $row = new BankRow();
                }
                
                $amount = (string)$ntry->Amt;
                $additional_info = "";
                $source_name = "";

                $row->amount = $amount;
                $row->type = (string)$ntry->CdtDbtInd;
                $row->booking_date = (string)$ntry->BookgDt->Dt;
                $row->validity_date = (string)$ntry->ValDt->Dt;
                
                if (!is_null($ntry->NtryDtls->TxDtls->RltdPties->TradgPty)) {
                    $source_name = (string)$ntry->NtryDtls->TxDtls->RltdPties->TradgPty->Nm;
                } 

                if (!is_null($ntry->AddtlNtryInf)) {
                    $additional_info = strip_tags((string)$ntry->AddtlNtryInf);
                } 

                // $row->source_name;
                $row->additional_info = trim($source_name.' '.$additional_info);
                $row->external_id = (string)$ntry->NtryRef;

                $row->save();
            }
        }

        // var_dump($rows);
        return ExitCode::OK;
    }

    public function readString(string $string)
    {
        $data = new SimpleXMLElement($string);
        return $data;
    }

    public function readFile(string $file)
    {
        $string = file_get_contents($file);
        return $this->readString($string);
    }
}
