<?php

namespace app\models;

use yii\db\ActiveRecord;

class BankRow extends ActiveRecord
{ 
    public $summary;
    public $tagname;
    
    public static function tableName()
    {
        return '{{bank_row}}';
    }

    public function getSum() {
        if ($this->type == 'DBIT') {
            return $this->amount * -1;
        } else {
            return $this->amount;
        }
        return $this->amount;
    }

    public function getReportingDate() {
        if ($this->document) {
            return $this->document->issued_at;
        }
        return $this->validity_date;
    }

    public function getTags() {
        return $this->hasMany(Tag::class, ['payment_id' => 'id']);
    }

    public function pair($document) {
        if (is_string($document)) {
            $document = Document::find()->where(['id' => $document])->one();
        }
        $this->document_id = $document->id;
        $this->save();
    }

    public function unpair() {
        $this->document_id = null;
        $this->save();
    }

    public function getDocument() {
        return $this->hasOne(Document::class, ['id' => 'document_id']);
    }
}