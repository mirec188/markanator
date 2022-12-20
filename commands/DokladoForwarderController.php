<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use \Ruler\RuleBuilder;
use \Ruler\Context;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DokladoForwarderController extends Controller
{
    private $rules = [
        [
            'when' => [
                0=>'bmail.from stringContains noreply@telekom.sk',
                1=>'attachment.filename endsWith pdf',
            ],
            'then' => 'forward'
        ], 
        [
            'when' => [
                0=>'bmail.from stringContains no-reply@upc.sk',
                1=>'attachment.filename endsWith pdf',
            ],
            'then' => 'forward',
        ],
        [
            'when' => [
                0=>'bmail.from stringContains helpdesk@websupport.sk',
                1=>'bmail.subject stringContains Faktúra',
                3=>'bmail.body stringDoesNotContain Oil',
                4=>'attachment.filename endsWith pdf'
            ],
            'then' => 'forward',
        ]
    ];
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $q = "SELECT * FROM bmail WHERE (doklado_sent IS NULL) or (doklado_sent=0) ORDER BY id desc";
        $bmails = \app\models\BMail::findBySql($q)->all();
        foreach ($bmails as $bmail) {
            $this->process($bmail);
            
        }
        
        return ExitCode::OK;
    }

    private function process($bmail) {
        foreach ($bmail->attachments as $attachment) {
            $results = $this->processRules($bmail, $attachment, $this->rules);
            if (!empty($results)) {
                print_r($results);
                $sent = $this->forward($bmail, $attachment);
                if ($sent) {
                    $bmail->doklado_sent = 1;
                    $bmail->save(false);
                }
            }
        }
    }

    private function processRules($bmail, $attachment, $rules) {
        $rb = new RuleBuilder;
        $results = [];
        foreach ($rules as $ruleX) {
            $whens = $ruleX['when'];
            $rule = null;
            foreach ($whens as $when) {
                $when = explode(' ', $when);
                $term = $when[0];
                $operator = $when[1];
                $value = $when[2];
                
                if ($rule == null) {
                    $rule = $rb->create($rb[$term]->$operator($value));
                } else {
                    $rule = $rb->create($rb->logicalAnd($rule, $rb[$term]->$operator($value)));
                }
            }   
            
            $context = new Context([
                'bmail.subject' => $bmail->subject,
                'bmail.from' => $bmail->sender,
                'bmail.body' => $bmail->body,
                'attachment.filename' => $attachment->filename,
            ]);

            $what = $rule->evaluate($context); 
            if ($what) {
                $res['rule'] = $ruleX;
                $res['context'] = [
                    'bmail.subject' => $bmail->subject,
                    'bmail.from' => $bmail->sender,
                    'attachment.filename' => $attachment->filename,
                ];
                $results[] = $res;
            }
        }

        return $results;
    }

    private function stringContains($haystack, $needle) {
        return strpos($haystack, $needle) !== false;
    }

    private function forward($bmail, $attachment) {
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = \Yii::$app->params['smpt_username'];
        $mail->Password = \Yii::$app->params['smpt_password'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom(\Yii::$app->params['mail_from'], 'Doklado forwarder');
        $mail->addAddress(\Yii::$app->params['mail_to']);
        
        $mail->Subject='Doklado faktura';
        $mail->Body='Doklado faktura';

        $attachmentPath = __DIR__.'/../'.$attachment->filename;
        // file_get_contents($attachmentPath);

        $mail->addAttachment($attachmentPath);
        // die();
        // Send the email
        return $mail->send();
    }

}
