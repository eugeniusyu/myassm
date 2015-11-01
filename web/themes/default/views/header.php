<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$page_title?> | <?=$Settings->site_name;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?= $assets ?>img/icon.png" />
	<link href="<?= $assets ?>css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>helpers/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>helpers/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>helpers/redactor/redactor.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>helpers/select2/css/select2.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>css/animate.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>css/hover-min.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>css/print.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>css/style.css" rel="stylesheet" type="text/css" />
	<script src="<?= $assets ?>js/jquery.js"></script>
</head>
<body>
	<section id="container">
		<header class="header orange-bg">
			<div class="container">
				<span class="logo bounceIn"><a href="<?=site_url();?>"><strong><?=$Settings->site_name;?></strong></a></span>
				<div class="nav notify-row pull-right" id="top_menu">
					<ul class="nav top-menu pull-right">
						<li class="dropdown bounceInRight">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-barcode"></i> <span class="hidden-xs"><?= lang('items'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('items'); ?>"><i class="fa fa-list"></i>  <?= lang('list_items'); ?></a></li>
								<li><a href="<?= site_url('items/add'); ?>"><i class="fa fa-plus"></i> <?= lang('add_item'); ?></a></li>
								<li><a href="<?= site_url('items/import'); ?>"><i class="fa fa-plus"></i> <?= lang('import_items'); ?></a></li>
							</ul>
						</li>
						<?php if ($qty_alert_num) { ?>
						<li class="bounceInRight">
							<a href="<?= site_url('items/alerts'); ?>"><i class="fa fa-bullhorn"></i><span class="badge bg-theme"><?= $qty_alert_num; ?></span></a>
						</li>
						<?php } ?>
						<li class="dropdown bounceInRight">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-arrow-circle-up"></i> <span class="hidden-xs"><?= lang('check_ins'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('check_in'); ?>"><i class="fa fa-list"></i>  <?= lang('list_check_ins'); ?></a></li>
								<li><a href="<?= site_url('check_in/add'); ?>"><i class="fa fa-plus"></i> <?= lang('new_check_in'); ?></a></li>
							</ul>
						</li>
						<li class="dropdown bounceInRight">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-arrow-circle-down"></i> <span class="hidden-xs"><?= lang('check_outs'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('check_out'); ?>"><i class="fa fa-list"></i>  <?= lang('list_check_outs'); ?></a></li>
								<li><a href="<?= site_url('check_out/add'); ?>"><i class="fa fa-plus"></i> <?= lang('new_check_out'); ?></a></li>
							</ul>
						</li>
						<?php if($Admin) { ?>
						<li class="dropdown bounceInRight">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-users"></i> <span class="hidden-xs"><?= lang('people'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('users'); ?>"><i class="fa fa-list"></i> <?= lang('list_users'); ?></a></li>
								<li><a href="<?= site_url('users/add'); ?>"><i class="fa fa-plus"></i>  <?= lang('add_user'); ?></a></li>
								<li><a href="<?= site_url('customers'); ?>"><i class="fa fa-list"></i> <?= lang('list_customers'); ?></a></li>
								<li><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-plus"></i>  <?= lang('add_customer'); ?></a></li>
								<li><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-list"></i> <?= lang('list_suppliers'); ?></a></li>
								<li><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-plus"></i>  <?= lang('add_supplier'); ?></a></li>
							</ul>
						</li>
						<li class="dropdown bounceInRight">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-cogs"></i> <span class="hidden-xs"><?= lang('settings'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('settings'); ?>"><i class="fa fa-cogs"></i> <?= lang('settings'); ?></a></li>
								<li><a href="<?= site_url('settings/categories'); ?>"><i class="fa fa-folder"></i> <?= lang('categories'); ?></a></li>
								<li><a href="<?= site_url('settings/add_category'); ?>"><i class="fa fa-plus"></i> <?= lang('add_category'); ?></a></li>
								<li><a href="<?= site_url('settings/backups'); ?>"><i class="fa fa-download"></i>  <?= lang('backups'); ?></a></li>
							</ul>
						</li>
						<?php } else { ?>
							<li class="dropdown bounceInRight">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<i class="fa fa-users"></i> <span class="hidden-xs"><?= lang('people'); ?></span>
								</a>
								<ul class="dropdown-menu extended pull-right">
									<div class="notify-arrow"></div>
									<li><a href="<?= site_url('customers'); ?>"><i class="fa fa-list"></i> <?= lang('list_customers'); ?></a></li>
									<li><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-plus"></i>  <?= lang('add_customer'); ?></a></li>
									<li><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-list"></i> <?= lang('list_suppliers'); ?></a></li>
									<li><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-plus"></i>  <?= lang('add_supplier'); ?></a></li>
								</ul>
							</li>
						<?php } ?>
						<li class="dropdown bounceInRight">
							<a data-toggle="dropdown" class="logout dropdown-toggle" href="#">
								<i class="fa fa-user"></i> <span class="hidden-xs capitalize">Hi! <?= $this->session->userdata('username'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('users/profile/'.$this->session->userdata('user_id')); ?>"><i class="fa fa-user"></i> <?= lang('profile'); ?></a></li>
								<li><a href="<?= site_url('logout'); ?>"><i class="fa fa-sign-out"></i> <?= lang('logout'); ?></a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</header>
		<section id="main-content">
			<section class="wrapper">
				<div class="container">
