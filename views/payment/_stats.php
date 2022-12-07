<?php 

$sumary = 0;
?>
<table class="table">
    <thead>
        <tr>
            <th>Tag</th>
            <th>Type</th>
            <th>Sum</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stats as $stat): ?>
            <?php 
            $sum = $stat->type == 'DBIT' ? $stat->summary*-1 : $stat->summary;
            $sumary += $sum; 
            ?>
            <tr>
                <td><?= $stat->tagname ?></td>
                <td><?= $stat->type ?></td>
                <td><?= $sum ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td></td>
            <td></td>
            <td><strong><?= $sumary ?></strong></td>
        </tr>
    </tbody>
</table>

<br/>

<?php $fullSum = 0; ?>
<?php foreach ($fullStats as $fullStat) { ?>
    <?php 
        $sum = $fullStat->type == 'DBIT' ? $fullStat->summary*-1 : $fullStat->summary;
        $fullSum += $sum;
    ?>
    <?= $fullStat->type ?>: <?= $sum ?> <br/>
<?php } ?>
<?= $fullSum ?>