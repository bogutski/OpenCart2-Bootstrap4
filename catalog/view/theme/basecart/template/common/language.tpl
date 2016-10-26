<?php if (count($languages) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-language">
  <div class="btn-group">
    <?php foreach ($languages as $language) { ?>
      <button type="button" class="btn btn-default language-select" name="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></button>
    <?php } ?>
  </div>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
<?php } ?>
