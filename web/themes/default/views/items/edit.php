<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="bounceInDown">
    <h3><i class="fa fa-plus"></i> <?= $page_title; ?></h3>
    <p><?= lang('update_info'); ?></p>
</div>
<div class="row zoomIn">
    <div class="col-lg-12">
        <div class="content-panel">
            <?= form_open_multipart("items/edit/".$item->id, 'class="validation"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("name", "name"); ?>
                                <?= form_input('name', set_value('name', $item->name), 'class="form-control tip" id="name" required="required"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("code", "code"); ?> (<?= lang('code_tip'); ?>)
                                <?= form_input('code', set_value('code', $item->code), 'class="form-control tip" id="code" required="required"'); ?>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group all">
                                <?= lang("barcode_symbology", "barcode_symbology") ?>
                                <?php
                                $bs = array('code25' => 'Code25', 'code39' => 'Code39', 'code128' => 'Code128', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca ' => 'UPC-A', 'upce' => 'UPC-E');
                                echo form_dropdown('barcode_symbology', $bs, set_value('barcode_symbology', $item->barcode_symbology), 'class="form-control select2" id="barcode_symbology" required="required" style="width:100%;"');
                                ?>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('category', 'category'); ?>
                                <?php
                                    $ct[0] = lang('select_category');
                                    foreach ($categories as $category) {
                                        $ct[$category->id] = $category->name.' ('.$category->code.')';
                                    }
                                ?>
                                <?= form_dropdown('category', $ct, set_value('category', $item->category_id), 'class="form-control tip" id="category"  required="required"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('quantity', 'quantity'); ?>
                                <?= form_input('quantity', set_value('quantity', $item->quantity), 'class="form-control tip" id="quantity"  required="required"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('unit', 'unit'); ?>
                                <?= form_input('unit', set_value('unit', $item->unit), 'class="form-control tip" id="unit"  required="required"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('alert_quantity', 'alert_quantity'); ?>
                                <?= form_input('alert_quantity', set_value('alert_quantity', $item->alert_quantity), 'onClick="this.select();" class="form-control tip" id="alert_quantity"  required="required"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('image', 'image'); ?>
                                <input name="userfile" type="file" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <p><?php echo form_submit('edit_item', lang('edit_item'), 'class="btn btn-theme03"'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
