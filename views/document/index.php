<?php 
$baseUrl = \Yii::getAlias('@web');
?>

<style>
    tr.odd td{
        background-color: #ececec;
    }
</style>
<div class="bd-content row" style="margin-bottom:10px">
    <div class="col-4">
        <select name="period" hx-trigger="change" hx-target="#document-rows" hx-get="<?=$baseUrl?>/document/rows" class="form-select">
        <?php 
        // get actual year
        $year = date('Y');
        // get actual month
        $month = date('m');
        ?>
        <option value="">all</option>
        <?php for ($y = $year; $y >= $year-2; $y--) { ?>
            <?php for ($m = 12; $m >= 1; $m--) { ?>
                <?php if ($y == $year && $m > $month) { echo $y.'--'.$m; break; } ?>
                <option value="<?= $m ?>-<?=$y;?>"><?= $m."/".$y ?></option>
            <?php } ?>
        <?php } ?>
            
        </select>
    </div>
</div>

<table id="example" class="table table-bordered table-condesed compact stripe table-sm table-hover dataTable dtr-inline" style="width:100%">
    <thead>
        <tr>
            <th>Supplier</th>
            <th>Created</th>
            <th>Delivered</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="document-rows">
        <?=$this->render('_rows', ['documents' => $documents]);?>
    </tbody>
</table>


<script>
<?php ob_start();?>

function initDatatable() {
    $('#example').DataTable({
        'pageLength': 50,
        columnDefs: [
            {
                targets: 1,
                render: DataTable.render.date('d.M.yyyy'),
            },
            {
                targets: 2,
                render: DataTable.render.date('d.M.yyyy'),
            }
        ],
    });
}
$(document).ready(function() {
    initDatatable();
});

htmx.on('htmx:beforeSwap', (evt) => {
    $('#example').DataTable().clear();
    $('#example').DataTable().destroy();
    return true;
})

htmx.on('htmx:afterSwap', (evt) => {
    initDatatable();
    return true;
})
<?php $script = ob_get_clean(); ?>
</script>
<?php $this->registerJs($script); ?>