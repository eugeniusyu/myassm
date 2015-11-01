<script>
    $(document).ready(function () {
        $('#spData').dataTable({
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[ 0, "desc" ]], "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('suppliers/get_suppliers') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, null, null, {"bSortable":false, "bSearchable": false}]
        });
    });
</script>

<div class="bounceInDown">
    <h3><i class="fa fa-users"></i> <?=$page_title;?><a href="<?=site_url('suppliers/add')?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?=lang('add_supplier');?></a></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row zoomIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="content-panel">
                <div class="table-responsive">
                    <table id="spData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th class="col-xs-3"><?= lang("name"); ?></th>
                            <th class="col-xs-2"><?= lang("phone"); ?></th>
                            <th class="col-xs-2"><?= lang("email_address"); ?></th>
                            <th style="width:65px;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>