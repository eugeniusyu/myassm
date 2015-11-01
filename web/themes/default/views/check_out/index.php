<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<?php
$v = "?v=1";

if ($this->input->post('from_date')) {
    $v .= "&start_date=" . $this->input->post('from_date');
}
if ($this->input->post('till_date')) {
    $v .= "&end_date=" . $this->input->post('till_date');
}
?>

<div class="bounceInDown">
    <h3><i class="fa fa-arrow-circle-down"></i> <?=$page_title;?><a href="<?=site_url('check_out/add')?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?=lang('add_check_out');?></a></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row zoomIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="content-panel">
                <div class="table-responsive">
                    <table id="TTable" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
                        <thead class="cf">
                            <tr>
                                <th><?=lang('id');?></th>
                                <th class="col-xs-3"><?=lang('date');?></th>
                                <th class="col-xs-2"><?=lang('reference');?></th>
                                <th class="col-xs-3"><?=lang('customer');?></th>
                                <th class="col-xs-2"><?=lang('created_by');?></th>
                                <th class="col-xs-2"><?=lang('note');?></th>
                                <th class="col-xs-1"><i class="fa fa-chain"></i></th>
                                <th class="col-xs-1"><?=lang('actions');?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th><?=lang('id');?></th>
                                <th></th><th></th><th></th><th></th><th></th>
                                <th><i class="fa fa-chain"></i></th>
                                <th><?=lang('actions');?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="text-center">
                    <div class="col-md-6 col-md-offset-3 no-print">
                        <?= form_open('check_out'); ?>
                            <div class="col-xs-5">
                                <input type="text" class="form-control date" value="<?= set_value('from_date') ?>" name="from_date" placeholder="<?= lang('from'); ?>">
                            </div>
                            <div class="col-xs-5">
                                <input type="text" class="form-control date" value="<?= set_value('till_date') ?>" name="till_date" placeholder="<?= lang('till'); ?>">
                            </div>
                            <div class="col-xs-2">
                                <button type="submit" class="btn btn-primary"><?= lang('get'); ?></button>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $assets ?>js/moment.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.date').datetimepicker({ format: 'YYYY-MM-DD' });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        function download(x) {
            if(x !== null) {
                return '<div class="text-center"><a href="<?=base_url('welcome/download');?>/'+x+'"><i class="fa fa-chain"></i></a></div>';
            }
            return '';
        }
        var oTable = $('#TTable').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": 10, 'bServerSide': true, 'bProcessing' : true,
            'sAjaxSource': '<?= site_url('check_out/get_list'); ?>',
            'fnServerData': function(sSource, aoData, fnCallback) {
                aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
                $.ajax ({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0]; nRow.className = "check_out_link";
                return nRow;
            },
            "aoColumns": [{"bVisible": false}, {"mRender": hrld}, null, null, null, {"bVisible": false}, {"mRender": download}, null]
        }).dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date').' (YYYY-MM-DD HH:SS)';?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('created_by');?>]", filter_type: "text", data: []},
          ], "footer");
    });
</script>
