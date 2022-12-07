<?php

namespace app\controllers;

use yii\web\Controller;
use \Yii;

class CsvController extends Controller
{

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
        // generate csv
        $csv = '';
        $first = true;
        foreach ($result as $row) {
            if ($first) {
                $first = false;
                $csv .= implode(';', array_keys($row)).PHP_EOL;
            }
            $csv .= implode(';', $row).PHP_EOL;
        }
        return $csv;
    }

}