<?php 
$baseUrl = \Yii::getAlias('@web');
?>
<?php foreach ($documents as $document) { ?>
<tr>
    <td>ID</td>
    <td><?=$document->organization_name;?></td>
    <td><?=$document->total_price;?></td>
    <td><?=$document->getDocumentDate();?></td>
    <td>
        <form>
            <a hx-trigger="click" hx-post="<?=$baseUrl?>/payment/pair-document?paymentId=<?=$payment->id;?>" href="javascript:void(0)">pair</a>
            <input type="hidden" name="documentId" value="<?=$document->id;?>">
            <input type="hidden" name="paymentId" value="<?=$payment->id;?>">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        </form>
    </td>
</tr>
<?php } ?>