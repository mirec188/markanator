<?php 
$baseUrl = \Yii::getAlias('@web');
?>
<div class="container mt-3">
  <!-- Create a form for the search input -->
  <!-- payment description -->
    <div class="row">
        <div class="col-12">
            <h3>Payment description</h3>
            <p><?=$payment->additional_info;?> <?=$payment->getSum();?> <?=$payment->validity_date;?></p>
            <div id="paired-doc">
                <?php if ($document = $payment->document) { ?>
                <form hx-target="#paired-doc" hx-trigger="click" hx-post="<?=$baseUrl?>/payment/unpair-document?paymentId=<?=$payment->id;?>">
                <p>
                <?="Document: ".$document->organization_name." ".$document->getDocumentDate(). " ".$document->total_price;?>
                <a style="color:red" href="javascript:void(0)">unpair</a>
                <input type="hidden" name="documentId" value="<?=$document->id;?>">
                <input type="hidden" name="paymentId" value="<?=$payment->id;?>">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                </p>
                </form>
                <?php } ?>
            </div>
        </div>

    </div>

  <form class="form-inline" hx-trigger="change, submit" hx-target="#pair-rows" hx-get="<?=$baseUrl?>/payment/pair-rows">
    <label class="sr-only" for="searchInput">Search:</label>
    <input type="hidden" name="paymentId" value="<?=$id;?>">
    <input type="text" name="fulltext" class="form-control mb-2 mr-sm-2" id="searchInput" placeholder="Enter search term">
    <button type="submit" class="btn btn-primary mb-2">Submit</button>
  </form>

  <!-- Create a table for the search results -->
  <table class="table mt-3">
    <thead>
      <tr>
        <th>ID</th>
        <th>Supplier name</th>
        <th>Amount</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="pair-rows">
      
    </tbody>
  </table>
</div>