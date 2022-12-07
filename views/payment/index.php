<style>
    tr.odd td{
        background-color: #ececec;
    }

    .tagify__tag{
    --tag-bg                  : #0052BF;
    --tag-hover               : #CE0078;
    --tag-text-color          : #FFF;
    --tags-border-color       : silver;
    --tag-border-radius       : 12px;
    --tag-text-color--edit    : #111;
    --tag-remove-bg           : var(--tag-hover);
    --tag-inset-shadow-size   : 1.35em;
    --tag-remove-btn-bg--hover: black;

    display: inline-block;
    min-width: 0;
    border: none;
    }

    tags.tagify {
        background-color: white;
    }
</style>
<?php 
$baseUrl = \Yii::getAlias('@web');
?>

<style>
    tr.odd td{
        background-color: #ececec;
    }
</style>
    <!-- fulltext search -->
    
<form id="search-form" hx-trigger="change, submit" hx-target="#payment-rows" hx-get="<?=$baseUrl?>/payment/rows">
<div class="bd-content row" style="margin-bottom:10px">
    <div class="col-4">
        <select name="period"  class="form-select">
            <?php 
            // get actual year
            $year = date('Y');
            // get actual month
            $month = date('m');

            ?>
            <option value="">all</option>
            <?php for ($y = $year; $y >= $year-2; $y--) { ?>
                <?php for ($m = $month; $m >= 1; $m--) { ?>
                    <option value="<?= $m ?>-<?=$y;?>"><?= $m."/".$y ?></option>
                <?php } ?>
            <?php } ?>
            
        </select>
    </div>
    <div class="col-4">
        <input type="text" name="fulltext" class="form-control" id="search" placeholder="Search">
    </div>
</div>
</form>


<div class="bd-content row" style="margin-bottom:10px">
    <div class="col-12">
        <table id="example" class="table table-bordered table-condesed compact stripe table-sm table-hover dataTable dtr-inline" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Info</th>
                    <th>Date</th>
                    <th>Actions</th>
                    <th>tags</th>
                    <th>documents</th>
                </tr>
            </thead>
            <tbody id="payment-rows">
                <?=$this->render('_rows', ['payments' => $payments]);?>
            </tbody>
        </table>
    </div>
</div>

<div class="bd-content row" style="margin-bottom:10px">
    <div class="col-12" id="payment-stats">
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pair a document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="pair-document-modal" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
<?php ob_start();?>

function updateTags(id, tags) {
    $.post('<?=$baseUrl?>/payment/tag', {
        id: id,
        tags: tags
    });
}
function initDatatable() {
    $('#example').DataTable({
        'pageLength': 50,
        'bFilter':false,
        columnDefs: [
            {
                targets: 4,
                render: DataTable.render.date('d.M.yyyy'),
            }
        ],
        "drawCallback": function( settings ) {
            document.querySelectorAll('.tgfy:not(.tagify)').forEach(function(el){
                let x = new Tagify(el, {
                    'callbacks': {
                        "blur":(e)=>{
                            updateTags(el.getAttribute("data-id"), e.detail.tagify.value);
                        },
                        "add":(e)=>{
                            updateTags(el.getAttribute("data-id"), e.detail.tagify.value);
                        },
                        "remove":(e)=>{
                            updateTags(el.getAttribute("data-id"), e.detail.tagify.value);
                        }
                    }
                });
            });
        }
    });
    
}
$(document).ready(function() {
    initDatatable();

    $('.tgfy').on('blur', function(e) {
        
    });

});

htmx.on('htmx:beforeSwap', (evt) => {
    if (evt.target.id == "payment-rows") {
        $('#example').DataTable().clear();
        $('#example').DataTable().destroy();
    }
    return true;
})

htmx.on('htmx:afterSwap', (evt) => {
    if (evt.target.id == "payment-rows") {
        initDatatable();
    }
    return true;
})

htmx.on('htmx:beforeSend', (evt) => {
    if (evt.target.id == "search-form") {
        $.get('<?=$baseUrl?>/payment/stats', $('#search-form').serialize(), function(data) {
            $('#payment-stats').html(data);
            return true;
        });
    }
    return true;
})

<?php $script = ob_get_clean(); ?>
</script>
<?php $this->registerJs($script); ?>