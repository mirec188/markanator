<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class PaymentController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        // Yii::$app->controller->enableCsrfValidation = false;
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        $payments = \app\models\BankRow::findBySql('SELECT * FROM bank_row')->all();
        return $this->render('index', ['payments' => $payments]);
    }

    public function actionRows() {
        $this->layout = false;
        // find by sql
        if (isset($_GET) && $_GET) {
            $period = !empty($_GET['period']) ? $_GET['period'] : false;
            $fulltext = !empty($_GET['fulltext']) ? $_GET['fulltext'] : false;
            // splid period by - . first is month second is year

            $paymentsQuery = \app\models\BankRow::find()->joinWith('tags');

            if ($period) {
                $period = explode('-', $period);
                $month = $period[0];
                $year = $period[1];
                $paymentsQuery->where(
                    'bank_row.id IN (
                        SELECT p.id FROM bank_row p where document_id IS NULL AND (MONTH(validity_date) = :month AND YEAR(validity_date)=:year)
                        UNION 
                        SELECT p.id FROM bank_row p 
                        JOIN document d ON p.document_id=d.id
                        where document_id IS NOT NULL AND MONTH(d.issued_at) = :month AND YEAR(d.issued_at)=:year
                    )',
                    ['month' => $month, 'year' => $year]
                );
            }

            if ($fulltext) {
                $paymentsQuery->andWhere(
                    "(additional_info" . " LIKE '%" . $fulltext . "%'"
                    ."OR type" . " LIKE '%" . $fulltext . "%'"
                    ."OR amount" . " LIKE '%" . $fulltext . "%'"
                    ."OR tag.name" . " LIKE '%" . $fulltext . "%'"
                    .")"
                );
            }

            $payments = $paymentsQuery->all();

        } else {
            $payments = \app\models\BankRow::findBySql('SELECT * FROM bank_row')->all();
        }
        return $this->render('_rows', ['payments' => $payments]);
    }

    public function actionTag() {
        if (isset($_POST)) {
            $id = $_POST['id'];
            $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
            $tagValues = [];
            foreach ($tags as $tag) {
                $tagValues[] = $tag['value'];
            }

            $actualTags = \app\models\Tag::find()->where(['payment_id' => $id])->all();
            foreach ($actualTags as $actualTag) {
                // if tag is not in new tags delete it
                if (!in_array($actualTag->name, $tagValues)) {
                    echo 'delete ' . $actualTag->name;
                    $actualTag->delete();
                }
            }
            // insert new tags not in actual tags
            foreach ($tags as $tag) {
                $actualTag = \app\models\Tag::find()->where(['payment_id' => $id, 'name' => $tag['value']])->one();
                if (!$actualTag) {
                    $newTag = new \app\models\Tag();
                    $newTag->payment_id = $id;
                    $newTag->name = $tag['value'];
                    $newTag->save();
                }
            }

        }
    }

    public function actionStatsByTags() {
        $this->layout = false;
        $tags = \Yii::$app->db->createCommand('SELECT distinct name FROM tag')->queryAll();
        foreach ($tags as $tag) {

        }
    }

    public function actionStats() {
        $this->layout = false;
        $statsQuery = \app\models\BankRow::find()->joinWith('tags')
            ->select(['SUM(amount) as summary', 'type', 'tag.name as tagname']);

        $fullStatsQuery = \app\models\BankRow::find()
            ->select(['SUM(amount) as summary', 'type']);

        if (isset($_GET) && $_GET) {
            $period = !empty($_GET['period']) ? $_GET['period'] : false;
            $fulltext = !empty($_GET['fulltext']) ? $_GET['fulltext'] : false;

            if ($period) {
                $period = explode('-', $period);
                $month = $period[0];
                $year = $period[1];
                $datesWhere =  'bank_row.id IN (
                    SELECT p.id FROM bank_row p where document_id IS NULL AND (MONTH(validity_date) = :month AND YEAR(validity_date)=:year)
                    UNION 
                    SELECT p.id FROM bank_row p 
                    JOIN document d ON p.document_id=d.id
                    where document_id IS NOT NULL AND MONTH(d.issued_at) = :month AND YEAR(d.issued_at)=:year
                )';
                
                $statsQuery->where($datesWhere, ['month' => $month, 'year' => $year]);
                $fullStatsQuery->where($datesWhere, ['month' => $month, 'year' => $year]);
            }

            if ($fulltext) {
                $statsQuery->andWhere(
                    "(additional_info" . " LIKE '%" . $fulltext . "%'"
                    ."OR type" . " LIKE '%" . $fulltext . "%'"
                    ."OR amount" . " LIKE '%" . $fulltext . "%'"
                    ."OR tag.name" . " LIKE '%" . $fulltext . "%'"
                    .")"
                );
                $fullStatsQuery->andWhere(
                    "(additional_info" . " LIKE '%" . $fulltext . "%'"
                    ."OR type" . " LIKE '%" . $fulltext . "%'"
                    ."OR amount" . " LIKE '%" . $fulltext . "%'"
                    .")"
                );
            }

            

        }
        $stats = $statsQuery->groupBy(['type', 'tagname'])->all();
        $fullStats = $fullStatsQuery->groupBy(['type'])->all();

        return $this->render('_stats', ['stats' => $stats, 'fullStats'=>$fullStats]);
    }

    public function actionPair() {
        $this->layout = false;
        $id = $_GET['paymentId'];
        $payment = \app\models\BankRow::findOne($id);
        return $this->render('_pair', ['id' => $id, 'payment'=>$payment]);
    }

    public function actionPairRows() {
        $this->layout = false;
        $id = $_GET['paymentId'];
        $payment = \app\models\BankRow::findOne($id);
        
        $fulltext = !empty($_GET['fulltext']) ? $_GET['fulltext'] : false;
        
        $service = new \app\services\DocumentService();

        if ($fulltext) {
            $documents = $service->findDocumentsByFulltext($fulltext);
        } else {
            $documents = [];
        }
        
        return $this->render('_pairDocumentRows', ['documents' => $documents, 'payment' => $payment]);
    }

    public function actionPairDocument() {
        $this->layout = false;
        $id = $_POST['paymentId'];
        $payment = \app\models\BankRow::findOne($id);
        $documentId = $_POST['documentId'];
        $document = \app\models\Document::findOne($documentId);
        
        if ($payment->pair($document)) {
            return "ok";
        }

        // return $this->render('_pairDocument', ['payment' => $payment, 'document' => $document]);
    }

    public function actionUnpairDocument() {
        $this->layout = false;
        $id = $_POST['paymentId'];
        $payment = \app\models\BankRow::findOne($id);
        $documentId = $_POST['documentId'];
        $document = \app\models\Document::findOne($documentId);
        
        if ($payment->unpair()) {
            return "unpaired";
        }

        // return $this->render('_pairDocument', ['payment' => $payment, 'document' => $document]);
    }

}