<?php

namespace app\controllers;

use yii\rest\ActiveController;
use \Yii;

class ApiController extends ActiveController
{

	public $modelClass = 'app\models\Product';

	public function actions() {
	    $actions = parent::actions();
	    // unset($actions['create']);
	    return $actions;
	}


    public function behaviors()
    {
        // remove rateLimiter which requires an authenticated user to work
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }

    public function actionGetDocuments() {

        $result = \Yii::$app->db->createCommand('SELECT * FROM document')->queryAll();
        return $result;
    }

}