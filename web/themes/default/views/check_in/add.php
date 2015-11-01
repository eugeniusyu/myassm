<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="bounceInDown">
    <h3><i class="fa fa-arrow-circle-up"></i> <?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>
<div class="row zoomIn">
    <div class="col-lg-12">
        <div class="content-panel">
            <?= form_open_multipart("check_in/add", 'class="validation"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <?= lang('date', 'date'); ?>
                            <?= form_input('date', set_value('date', date('Y-m-d H:i')), 'class="form-control tip" id="date"  required="required"'); ?>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <?= lang('reference', 'reference'); ?>
                            <?= form_input('reference', set_value('reference', $reference), 'class="form-control tip" id="reference" required="required" onClick="this.select();"'); ?>
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('supplier', 'supplier'); ?>
                                <?php 
                                $sp[''] = lang('select_supplier');
                                foreach ($suppliers as $supplier) {
                                    $sp[$supplier->id] = $supplier->name;
                                }
                                ?>
                                <?= form_dropdown('supplier', $sp, set_value('supplier'), 'class="form-control tip" id="supplier"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('attachment', 'attachment'); ?>
                                <input name="attachment" type="file" id="attachment" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('add_item', 'add_item'); ?>
                                <?= form_input('add_item', '', 'class="form-control tip" id="add_item" placeholder="'.lang('search_product_or_scan').'"'); ?>
                            </div>
                        </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="inTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th><?= lang('description'); ?></th>
                                                <th class="col-xs-2"><?= lang('quantity'); ?></th>
                                                <th style="width:25px;"><i class="fa fa-trash-o"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4"><?= lang('add_product_by_searching_above_field'); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                   
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('note', 'note'); ?>
                                <?= form_textarea('note', set_value('note'), 'class="form-control redactor tip" id="note" style="height:100px;"'); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <p><?php echo form_submit('check_in', lang('add_check_in'), 'class="btn btn-theme03"'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $assets ?>js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>js/moment.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var stinitems = {};
    var lang = new Array();
    lang['code_error'] = '<?= lang('code_error'); ?>';
    lang['r_u_sure'] = '<?= lang('r_u_sure'); ?>';
    lang['no_match_found'] = '<?= lang('no_match_found'); ?>';
    $(document).ready(function() {
        if (get('stinitems')) { remove('stinitems'); }
        loadInItems();
    });
</script>
<script src="<?= $assets ?>js/stin.js" type="text/javascript"></script>