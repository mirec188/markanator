<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Document;

class DocumentController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'index'],
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
        $documents = Document::findBySql('SELECT * FROM document')->all();
        return $this->render('index', ['documents' => $documents]);
    }

    public function actionRows() {
        $this->layout = false;
        if (isset($_GET['period']) && $_GET['period']) {
            $period = $_GET['period'];
            // splid period by - . first is month second is year
            $period = explode('-', $period);
            $month = $period[0];
            $year = $period[1];
            $documents = Document::findBySql('SELECT * FROM document 
                WHERE (delivery_date is not null and MONTH(delivery_date) = :month AND YEAR(delivery_date) = :year)
                OR (delivery_date is null AND MONTH(issued_at) = :month AND YEAR(issued_at) = :year)
                ', [':month' => $month, ':year' => $year])->all();
        } else {
            $documents = Document::findBySql('SELECT * FROM document')->all();
        }
        return $this->render('_rows', ['documents' => $documents]);
    }
}