
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('list_results'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="TTable" class="table table-striped table-bordered table-hover" style="margin-bottom:5px;">
                            <thead>
                            <tr>
                                <th><?= lang('code'); ?></th>
                                <th><?= lang('name'); ?></th>
                                <th style="width:100px;"><?= lang('actions'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('#TTable').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": 10, 'bServerSide': true, 'bProcessing' : true,
            'sAjaxSource': '<?= site_url('settings/get_categories'); ?>',
            'fnServerData': function(sSource, aoData, fnCallback) {
                aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
                $.ajax ({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
            },
            "aoColumns": [null, null, null]
        });
    });
</script>