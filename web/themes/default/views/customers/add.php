<div class="bounceInDown">
	<h3><i class="fa fa-plus"></i> <?=$page_title;?></h3>
	<p><?= lang('enter_info'); ?></p>
</div>
<div class="row zoomIn">
	<div class="row">
		<div class="col-lg-12">
			<div class="content-panel">
				<?php echo form_open("customers/add");?>

				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label" for="code"><?= $this->lang->line("name"); ?></label>
						<?= form_input('name', set_value('name'), 'class="form-control input-sm" id="name"'); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label" for="email_address"><?= $this->lang->line("email_address"); ?></label>
						<?= form_input('email', set_value('email'), 'class="form-control input-sm" id="email_address"'); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label" for="phone"><?= $this->lang->line("phone"); ?></label>
						<?= form_input('phone', set_value('phone'), 'class="form-control input-sm" id="phone"');?>
					</div>
				</div>
				</div>

				<div class="col-md-12">
					<div class="form-group">
						<?php echo form_submit('add_customer', $this->lang->line("add_customer"), 'class="btn btn-primary"');?>
					</div>
				</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>