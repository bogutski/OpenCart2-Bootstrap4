<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form').attr('action', '<?php echo $copy; ?>').submit()"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div id="notification"></div>

    <div class="btn-toolbar pull-left" role="toolbar">
      <div class="btn-group">
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#product-filter-setting-modal"><?php echo $button_filter_setting; ?></button>
      </div>
      <div class="btn-group">
        <?php echo $advanced_filter->renderShownColumnsInputs(); ?>
      </div>
    </div>
    <div class="pull-right">
        <?php echo $paypal_button; ?>
    </div>
    <div class="clearfix"></div>

    <script src="view/javascript/jquery.multiple.select.js"></script>
    <?php if($advanced_filter) { ?>
      <div class="product-filter">
        <?php echo $advanced_filter->renderInputs($token); ?>
      </div>

      <div class="filter-panel">
        <a id="advanced-filter-button" class="btn btn-success"><?php echo $button_filter; ?></a>
        <a id="show-filter" class="btn btn-primary"></a>
        <a id="clear-all-filters" class="btn btn-warning"><?php echo $clear_all_filters_text; ?></a>
        <select id="product-sort-type" name="sort_type">
            <option value="ASC"><?php echo $asc_text; ?></option>
            <option value="DESC"><?php echo $desc_text; ?></option>
        </select>
        <input id="products-per-page" name="products-per-page" type="text" value="<?php echo $limit_per_page; ?>" />
      </div>
    <?php } ?>

    <div id="products">
      <?php echo $product_table; ?>
    </div>
  </div>

  <div class="modal fade" id="product-filter-setting-modal" tabindex="-1" role="dialog" aria-labelledby="productFilterSetting">
  </div><!-- /.modal -->
</div>

<script type="text/javascript"><!--
(function($){
  $('#column-show').multipleSelect({
    filter: true,
    width: 200,
    countSelected: '<?php echo $count_selected_text; ?>',
    placeholder: '<?php echo $select_placeholder_text; ?>',
    allSelected: '<?php echo $all_selected_text; ?>'
  });

  $('.product-filter .date').datetimepicker({
    pickTime: false
  });

  /*Show/Hide filter options*/
  $('.product-filter').hide();
  $('#show-filter').html('Show filter');
  $('#show-filter').click(function(e){
    e.preventDefault();

    if($('.product-filter').is(':hidden')) {
      $(this).html('<?php echo $hide_filters_text; ?>');
      $('.product-filter').show();
    } else {
      $(this).html('<?php echo $show_filters_text; ?>');
      $('.product-filter').hide();
    }
  });

  /*MODAL - Select all checbox click*/
  $('#product-filter-setting-modal').on('click', '#select-all-enable', function(){
    $('.enable').attr('checked', this.checked);
  });

  $('#product-filter-setting-modal').on('click', '#select-all-column', function(){
    $('.show-column').attr('checked', this.checked);
  });

  $('#product-filter-setting-modal').on('show.bs.modal', function (e) {
    $.ajax({
      url: 'index.php?route=extension/module/product_filter&mode=modal&token=<?php echo $token; ?>',
      type: 'get',
      dataType: 'html',
      success: function(html) {
        $('#product-filter-setting-modal').html(html);
      }
    });
  })

  /*Clear all filters and rules*/
  $('#clear-all-filters').click(function(e){
    e.preventDefault();

    $('.product-filter select').each(function(index) {
      if($(this).is('[multiple]')) {
        $(this).multipleSelect('uncheckAll');
      } else {
        $(this).val('');
      }
    });
    $('.product-filter input[type!=checkbox]').val('');
    $('.product-filter input[type=checkbox]:checked').attr('checked', false);

  });

  /*Select all checbox click*/
  $('#products').on('click', '#select-all', function(){
    $('input[name*=selected]').attr('checked', this.checked);
  });

  function Filter() {
    this.page = 1;
    this.paginationClick = false;

    this.init = function(){
      var self = this;
      self.initCtrls();
    }

    /*Filtering actions*/
    this.initCtrls = function(){
      var self = this;
      /*Filter button click*/
      $('#advanced-filter-button').click(function(e) {
        if(!self.paginationClick) {
          self.page = 1;
        }
        self.paginationClick = false;

        var filter_data = $.param($('.product-filter select, .product-filter input[type=text], .product-filter input[type=checkbox]:checked, .product-filter input[type=radio]:checked, input[name*=\'sort\']:checked, #product-sort-type')) + '&page=' + self.page + '&limit=' + $('#products-per-page').val();
        $.ajax({
          url: 'index.php?route=extension/module/product_filter/filterProducts&token=<?php echo $token; ?>',
          type: 'post',
          data: filter_data,
          dataType: 'json',
          beforeSend: function() {
            $('#products').hide();
            $('#products').before('<span class="wait" style="margin: 0 0 0 43%"><img src="view/image/advanced_filter/filtering.gif" alt="Filtering" /></span>');
          },
          complete: function() {
            $('#products').show();
            $('.wait').remove();
          },
          success: function(json) {
            $('#notification .warning').remove();

            if(json == null) {
              $('#notification').html('<div class="warning">Content is null</div>');
            } else if(json['error']) {
              $('#notification').html('<div class="warning">' + json['error'] + '</div>');
            } else {
              $('#products').html(json);
              $("html, body").animate({scrollTop: $(".filter-panel").offset().top}, "slow");
            }
          }
        });
      });

      $('#product-sort-type').on('change', function() {
        if($('input[name*=sort]:checked').length != 0) {
          self.paginationClick = true;
          $('#advanced-filter-button').click();
        }
      });

      /*Sort checkbox change*/
      $('#products').on('click', 'input[name*=sort]', function() {
        var checked = $('input[name*=sort]:checked').length != 0;

        $('input[name*=sort]').prop('checked', false);
        $(this).prop("checked", checked);
        self.paginationClick = true;
        $('#advanced-filter-button').click();
      });

      /*Column checkbox click*/
      $('#column-show').on('change', function() {
        var selected = $('#column-show').val();
        $.ajax({
          url: 'index.php?route=extension/module/product_filter/changeShownColumns&token=<?php echo $token; ?>',
          type: 'post',
          data: {columns_shown: selected, page: self.page, limit: $('#products-per-page').val()},
          dataType: 'json',
          success: function(json) {
            $('#notification .warning').remove();

            if(json == null) {
              $('#notification').html('<div class="warning">Content is null</div>');
            } else if(json['error']) {
              $('#notification').html('<div class="warning">' + json['error'] + '</div>');
            } else {
              $('#products').html(json);

              $('#products .date').datetimepicker({
                pickTime: false
              });
            }
          }
        });
      });

      /*Page click*/
      $('#products').on('click', '.pagination a', function(e){
        e.preventDefault();

        self.paginationClick = true;
        self.page = $(this).attr('href');

        $('#advanced-filter-button').click();
        return false;
      });
    }
  }

  /*Inline editing*/
  function InlineEdit() {
    this.field = null;
    this.fname = null;
    this.fprev_content = null;
    this.fcur_content = null;
    this.opened = false;

    this.init = function(){
      var self = this;
      self.initCtrls();
    }

    this.initCtrls = function(){
      var self = this;

      $('#products').on('dblclick', '.editable', function(e){
        e.preventDefault();

        if(!self.opened) {
          self.field = $(this);
          self.fname = $(this).attr('data-field');
          self.edited_value_field = $(this).hasClass('js-edited-value') ? $(this) : $(this).find('.js-edited-value');
          self.fprev_content = $(this).html();
          self.fcur_content = null;

          var content = self.edited_value_field.html();

          if(self.fname == 'category_id') {
            var categoryData = [];
            $('.category-value', $(this)).each(function(index){
              categoryData.push({
                'category_id': parseInt($(this).attr('data-id')),
                'name': $(this).html().replace(/&gt;/g, ';arrow;')
              });
            });
            content = categoryData;
          }

          $.ajax({
            url: 'index.php?route=extension/module/product_filter/createEditField&token=<?php echo $token; ?>',
            type: 'post',
            data: {name: self.fname, content: content},
            dataType: 'json',
            success: function(json) {
              $('#notification .warning').remove();

              if(json == null) {
                $('#notification').html('<div class="warning">Content is null</div>');
              } else if(json['error']) {
                $('#notification').html('<div class="warning">' + json['error'] + '</div>');
              } else {
                self.field.html(json);
                self.showLayer();
                self.opened = true;
                $('#products .date').datetimepicker({
                  pickTime: false
                });
              }
            }
          });
        }
      });

      $('#products').on('click', '.editable .js-inline-edit-button', function(e){
        e.preventDefault();

        if(self.opened) {
          if($(this).attr('id') == 'field_save') {
            var inputName = $('#edit-field').parent().attr('data-field');
            var content = $('#filter_' + inputName + '_edit').val();
            var productId = $('#edit-field').closest('tr').attr('id');

            if(inputName == 'category_id') {
              var categoryData = [];
              $("input[name='category_id[]']").each(function(index){
                categoryData.push($(this).val());
              });
              content = categoryData;
            }else if(inputName == 'image' && content == '') {
              content = $('#filter_' + inputName + '_edit').attr('data-empty-image');
            }

            $.ajax({
              url: 'index.php?route=extension/module/product_filter/updateEditField&token=<?php echo $token; ?>',
              type: 'post',
              data: {productId: productId, inputName: inputName, content: content},
              dataType: 'json',
              success: function(json) {
                $('#notification .warning').remove();

                if(json == null) {
                  $('#notification').html('<div class="warning">Content is null</div>');
                } else if(json['error']) {
                  $('#notification').html('<div class="warning">' + json['error'] + '</div>');
                  $('#edit-field').parent().html(self.fprev_content);
                } else {
                  self.fcur_content = json;
                  $('#edit-field').parent().html(self.fcur_content);
                }
                self.hideLayer();
              }
            });
          } else if($(this).attr('id') == 'field_cancel') {
            $('#edit-field').parent().html(self.fprev_content);
            self.hideLayer();
          }
          self.opened = false;
        }
      });
    }

    this.showLayer = function() {
        $('#layer').remove();
        var $layer = $('<div />').attr({
            'id' : 'layer'
        }).height($(document).height());

        $('body:first').append($layer);

        //cancel editing on click
        $layer.fadeTo(250, 0.65).click(function() {
            $('#field_cancel').click();
        });
    }

    this.hideLayer = function() {
        $('#layer').fadeOut(500, function() {
            $('#layer').remove();
        });
    }
  }

  $(function(){
    var filter = new Filter();
    var inline_edit = new InlineEdit();

    filter.init();
    inline_edit.init();
  });
})(jQuery);

//--></script>
<?php echo $footer; ?>
