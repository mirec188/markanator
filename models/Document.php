<?php

namespace app\models;

use yii\db\ActiveRecord;

class Document extends ActiveRecord
{ 
    public static function tableName()
    {
        return '{{document}}';
    }

    public function getTags() {
        return $this->hasMany(Tag::class, ['document_id' => 'id']);
    }

    public function getDocumentDate() {
        return $this->delivery_date == null ? $this->issued_at : $this->delivery_date;
    }

    public function getBankRows() {
        return $this->hasMany(BankRow::class, ['document_id' => 'id']);
    }

    public function getBankRow() {
        return $this->bankRows ? $this->bankRows[0] : null;
    }

}