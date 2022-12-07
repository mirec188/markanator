<?php 

namespace app\services;

class DocumentService {

    // find all documents by fulltext query, split words, search in additional_info, amount
    public function findDocumentsByFulltext($fulltext) {
        // split fulltext by words
        $words = explode(' ', $fulltext);
        // find documents where organization_name or amount is like any of the words
        $documentsQuery = \app\models\Document::find();
        foreach ($words as $word) {
            $documentsQuery->andWhere(
                "(organization_name" . " LIKE '%" . $word . "%'"
                ."OR total_price" . " LIKE '%" . $word . "%'"
                .")"
            );
        }
        return $documentsQuery->all();
    }

}