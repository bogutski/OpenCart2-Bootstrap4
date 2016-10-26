<?php if (count($currencies) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-currency">
  <div class="btn-group">
      <?php foreach ($currencies as $currency) { ?>
      <?php if ($currency['symbol_left']) { ?>
      <button type="button" class="btn btn-default currency-select" name="<?php echo $currency['code']; ?>"><?php echo $currency['code']; ?></button>
      <?php } else { ?>
      <button type="button" class="btn btn-default currency-select" name="<?php echo $currency['code']; ?>"><?php echo $currency['code']; ?></button>
      <?php } ?>
      <?php } ?>
  </div>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
<?php } ?>
