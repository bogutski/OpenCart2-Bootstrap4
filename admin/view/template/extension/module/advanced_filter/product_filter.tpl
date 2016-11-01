<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-advance-product-filter" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <span style="float:right"><?php echo $paypal_button; ?></span>
    <div class="paypal">
      <span style="float:left">Paypal Donate Button Enabled <input type="checkbox" name="paypal" <?php echo $paypal_enabled ? "checked" : ""; ?> /></span>     
    </div>
    <div class="clearfix"></div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-advance-product-filter" class="form-horizontal">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_field_name; ?></td>
                  <td class="text-left"><?php echo $column_autocomplete; ?></td>
                  <td class="text-left"><?php echo $column_inline_edit; ?></td>
                  <td class="text-left"><?php echo $column_show_column; ?><input type="checkbox" id="select-all-column" style="float:right" /></td>
                  <td class="text-left"><?php echo $column_enable; ?><input type="checkbox" id="select-all-enable" style="float:right" /></td>
                </tr>
              </thead>
              <?php foreach ($fields as $name => $field) { ?>
              <tbody>
                <tr>
                  <td class="text-left"><?php echo $field['entry_text']; ?></td>
                  <td class="text-left"><i class="fa fa-<?php echo ($field['autocomplete'] ? 'check' : 'times'); ?>" area-hidden="true"></i></td>
                  <td class="text-left"><i class="fa fa-<?php echo ($field['inline_edit'] ? 'check' : 'times'); ?>" area-hidden="true"></i></td>
                  <td class="text-left"><?php if($field['show_column']) { ?> <input type="checkbox" name="status[<?php echo $name; ?>][column]" value="1" checked> <?php } else { ?> <input type="checkbox" class="show-column" name="status[<?php echo $name; ?>][column]" value="0"> <?php } ?></td>
                  <td class="text-left"><?php if($field['enable']) { ?> <input type="checkbox" name="status[<?php echo $name; ?>][enable]" value="1" checked> <?php } else { ?> <input type="checkbox" class="enable" name="status[<?php echo $name; ?>][enable]" value="0"> <?php } ?></td>
                </tr>
              </tbody>
              <?php } ?>
            </table>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
  /*Select all checbox click*/
  $('#select-all-enable').click(function(){
    $('.enable').attr('checked', this.checked);
  });

  $('#select-all-column').click(function(){
    $('.show-column').attr('checked', this.checked);
  });
})
</script>
<?php echo $footer; ?>