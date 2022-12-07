<?php 
$baseUrl = \Yii::getAlias('@web');
?>
<?php foreach ($payments as $payment): ?>
    <?php 
    $tagModels = $payment->tags;
    $tags="";
    $tagNames = [];
    foreach ($tagModels as $model) {
        $tagNames[] = $model->name;
    }
    $tags = implode(',', $tagNames);
    ?>
    <tr>
        <td><?= $payment['id'] ?></td>
        <td><?= $payment->getSum()?></td>
        <td><?= $payment['type'] ?></td>
        <td><?= $payment['additional_info'] ?></td>
        <td><?= $payment->getReportingDate() ?></td>
        <td>
            <a href="/document/<?= $payment['id'] ?>">View</a>
        </td>
        <td><input data-id="<?=$payment->id;?>" class="tgfy customLook"  value="<?=$tags;?>"/></td>
        <td>
            <a hx-trigger="click" hx-get="<?=$baseUrl?>/payment/pair?paymentId=<?=$payment->id;?>" hx-target="#pair-document-modal" href="javascript:void(0)" class="pair" data-id="<?=$payment->id;?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <?php if ($payment->document) { ?>
                <span style="font-weight:bold; color:green; font-size:20px">&#x2611;</span>
            <?php } ?>
            Pair
        </a>
        </td>
    </tr>
<?php endforeach; ?>
