<?php

namespace app\models;

use yii\db\ActiveRecord;

class BMail extends ActiveRecord
{ 
    public static function tableName()
    {
        return '{{bmail}}';
    }

    public function getAttachments()
    {
        return $this->hasMany(BMailAttachment::class, ['bmail_id' => 'id']);
    }

}