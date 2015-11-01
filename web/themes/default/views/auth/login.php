<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <script type="text/javascript">if(parent.frames.length!==0){top.location='<?=site_url('login')?>';}</script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $assets ?>img/icon.png" />
    <link href="<?= $assets ?>css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>helpers/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>css/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>helpers/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>css/style.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-theme02">
    <div id="login-page">
        <div class="container">
            <?php echo form_open("auth/login", 'class="form-login zoomIn"'); ?>
            <h2 class="form-login-heading" style="font-weight:900;"><?= $Settings->site_name; ?></h2>
            <div class="login-wrap">
            <p><?=lang('login_to_your_account'); ?></p>
                <input type="text" class="form-control" name="identity" placeholder="<?=lang('email'); ?>" autofocus>
                <br>
                <input type="password" class="form-control" name="password" placeholder="<?=lang('password'); ?>">
                <label class="checkbox">
                    <a class="pull-right" id="ft_pd" data-toggle="modal" href="login.html#myModal"><i class="fa fa-key"></i> <?=lang('forgot_password'); ?></a>
                </label>
                <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> <?=lang('sign_in'); ?></button>
            </div>
            <?php echo form_close(); ?>

            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <?php echo form_open("auth/forgot_password"); ?>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x fa-times"></i></button>
                            <h4 class="modal-title"><?=lang('forgot_password'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <p><?= lang('forgot_password_heading'); ?></p>
                            <input type="email" name="forgot_email" placeholder="<?=lang('email'); ?>" autocomplete="off" class="form-control placeholder-no-fix">

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-theme" type="submit"><?=lang('submit'); ?></button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    <script src="<?= $assets ?>js/jquery.js"></script>
    <script src="<?= $assets ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>helpers/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            <?php if(isset($message)) { ?>
                var success_noti = $.gritter.add({
                    title: 'Success!',
                    text: '<?=str_replace(array("\r", "\n"), "", $message);?>',
                    image: '',
                    sticky: true,
                    time: '',
                    class_name: 'bg-theme03 border-round'
                });
                <?php } ?>
                <?php if(isset($error)) { ?>
                    var error_noti = $.gritter.add({
                        title: 'Error!',
                        text: '<?=str_replace(array("\r", "\n"), "", $error);?>',
                        image: '',
                        sticky: true,
                        time: '',
                        class_name: 'bg-theme04 border-round'
                    });
                    <?php } ?>
                    return false;
                });
    </script>
</body>
</html>
