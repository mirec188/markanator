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
use \Ruler\RuleBuilder;
use \Ruler\Rule;
use \Ruler\Context;
use app\models\Tag;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TagRulesController extends Controller
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
        $payments = BankRow::findBySql($q)->all();

        // manual rules
        foreach ($payments as $payment) {
            $this->containsStringsRule($payment, "additional_info", 'apple', 'apple');
            $this->containsStringsRule($payment, "additional_info", 'OCULUS', 'hryafilmy');
        }

        // automatic rules by old settings
        foreach ($payments as $payment) {
            $otherPayments = BankRow::find()->where(["additional_info" => $payment->additional_info])->all();
            foreach ($otherPayments as $otherPayment) {
                foreach ($payment->tags as $tag) {
                    if ($tag->name == "benzin" && $otherPayment->amount > 30) {
                        $this->putTagIfNotExists($otherPayment, $tag->name);
                    }
                    if ($tag->name != "benzin") {
                        $this->putTagIfNotExists($otherPayment, $tag->name);
                    }
                }
            }
        }


        return ExitCode::OK;
    }

    private function containsStringsRule($payment, $field, $string, $tagName)
    {
        $string = strtolower($string);
        $rb = new RuleBuilder();

        $rule = $rb->create(
            $rb->logicalAnd(
                $rb[$field]->stringContains($string),
            ),
            function() use ($payment, $tagName) {
                $this->putTagIfNotExists($payment, $tagName);
            }
        );
        
        $context = new Context([
            $field => strtolower($payment->$field),  
            "payment"=>$payment,
        ]);
        
        $rule->execute($context); // "Yay!"
    }

    private function equalsStringsRule($payment, $field, $string, $tagName)
    {
        $string = strtolower($string);
        $rb = new RuleBuilder();

        $rule = $rb->create(
            $rb->logicalAnd(
                $rb[$field]->equalTo($string),
            ),
            function() use ($payment, $tagName) {
                $this->putTagIfNotExists($payment, $tagName);
            }
        );
        
        $context = new Context([
            $field => strtolower($payment->$field),  
            "payment"=>$payment,
        ]);
        
        $rule->execute($context); // "Yay!"
    }

    private function putTagIfNotExists($payment, $tagName) {
        $q = "SELECT * FROM tag WHERE payment_id={$payment->id} and tag.name = '".$tagName."'";
        $tag = Tag::findBySql($q)->one();
        if (!$tag) {
            $tag = new Tag();
            $tag->payment_id = $payment->id;
            $tag->name = $tagName;
            $tag->save();
        }
    }

  
}
