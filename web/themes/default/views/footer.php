<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
</div>
</section>
<div class="clearfix"></div>
</section>
<footer class="site-footer bounceInUp">
  <div class="text-center">
    &copy; <?= date('Y').' '.$Settings->site_name; ?>
    <!-- <a href="#" class="go-top"><i class="fa fa-angle-up"></i></a> -->
  </div>
</footer>
</section>
<div id="jp"></div>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>helpers/redactor/redactor.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>helpers/select2/js/select2.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>helpers/gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/formValidation.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/common-scripts.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom-scripts.js"></script>
<script type="text/javascript">
    var base_url = '<?=base_url();?>', rows_per_page = <?= $Settings->rows_per_page ?>;
    var dateformat = '<?= $Settings->dateformat ?>',  timeformat = '<?= $Settings->timeformat ?>';
    var message = '<?= isset($message) ? str_replace(array("\r", "\n"), "", $message) : ''; ?>';
    var error = '<?= isset($error) ? str_replace(array("\r", "\n"), "", $error) : ''; ?>';
    var warning = '<?= isset($warning) ? str_replace(array("\r", "\n"), "", $warning) : ''; ?>';
    var lang = new Array();
    lang['code_error'] = '<?= lang('code_error'); ?>';
    lang['r_u_sure'] = '<?= lang('r_u_sure'); ?>';
    lang['no_match_found'] = '<?= lang('no_match_found'); ?>';
    lang['unexpected_value'] = '<?= lang('unexpected_value'); ?>';
</script>
</body>
</html>
