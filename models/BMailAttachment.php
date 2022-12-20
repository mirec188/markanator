<?php

namespace app\models;

use yii\db\ActiveRecord;

class BMailAttachment extends ActiveRecord
{ 
    public static function tableName()
    {
        return '{{bmail_attachment}}';
    }


}