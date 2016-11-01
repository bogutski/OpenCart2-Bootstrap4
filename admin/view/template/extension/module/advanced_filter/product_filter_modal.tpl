<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_cancel; ?>"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title"><?php echo $text_list; ?></h4>
    </div>
    <div class="modal-body">
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
      <form action="<?php echo $action; ?>" name="filter-setting-form" method="post">
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
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_cancel; ?></button>
      <button type="submit" onclick="$('form[name=\'filter-setting-form\']').submit()" class="btn btn-primary"><?php echo $button_save; ?></button>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->