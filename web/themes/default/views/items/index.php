<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<style type="text/css">
    .table td:first-child { padding: 3px; }
    .table td:last-child { padding: 6px; }
    .table td:first-child, .table td:nth-child(5), .table td:nth-child(6), .table td:nth-child(7), .table td:last-child { text-align: center; }
</style>
<div class="bounceInDown">
    <h3><i class="fa fa-barcode"></i> <?=$page_title;?><a href="<?=site_url('items/add')?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?=lang('add_item');?></a></h3>
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
                                <th class="col-xs-1"><?=lang('image');?></th>
                                <th class="col-xs-1"><?=lang('code');?></th>
                                <th class="col-xs-3"><?=lang('name');?></th>
                                <th class="col-xs-2"><?=lang('category');?></th>
                                <th class="col-xs-1"><?=lang('quantity');?></th>
                                <th class="col-xs-1"><?=lang('unit');?></th>
                                <th class="col-xs-1"><?=lang('alert_on');?></th>
                                <th class="col-xs-2"><?=lang('actions');?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th><?=lang('image');?></th>
                                <th></th><th></th><th></th>
                                <th></th><th></th><th></th>
                                <th><?=lang('actions');?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="picModal" tabindex="-1" role="dialog" aria-labelledby="picModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel">title</h4>
            </div>
            <div class="modal-body text-center">
                <img id="product_image" src="" alt="" class="img-responsive" />
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        function image(n) {
            if(n !== null && n != '') {
                return '<div style="width:32px; margin: 0 auto;"><a href="<?=base_url();?>uploads/'+n+'" class="open-image"><img src="<?=base_url();?>uploads/'+n+'" alt="" class="img-responsive"></a></div>';
            }
            return '';
        }
        $('#TTable').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": 10, 'bServerSide': true, 'bProcessing' : true,
            'sAjaxSource': '<?= site_url('items/get_items'); ?>',
            'fnServerData': function(sSource, aoData, fnCallback) {
                aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
                $.ajax ({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
            },
            "aoColumns": [{"mRender": image}, null, null, null, null, null, null, null]
        }).dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('code');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('name');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('category');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('quantity');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('unit');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('alert_on');?>]", filter_type: "text", data: []},
          ], "footer");
        $('#TTable').on('click', '.open-image', function(e) {
            e.preventDefault();
            var a_href = $(this).attr('href');
            var code = $(this).closest('tr').children('td:eq(1)').text();
            var name = $(this).closest('tr').children('td:eq(2)').text();
            $('#myModalLabel').text(name+' ('+code+')');
            $('#product_image').attr('src',a_href);
            $('#picModal').modal();
            return false;
        });
    });
</script>
