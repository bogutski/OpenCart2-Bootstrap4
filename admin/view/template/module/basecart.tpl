<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-basecart" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-basecart" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-nav"><?php echo $entry_nav; ?></label>
            <div class="col-sm-10">
            <select name="basecart_module_nav" id="input-nav" class="form-control">
                <option value="basecart_module_navdefault" <?php echo ($basecart_module_nav == 'basecart_module_navdefault' ? 'selected' : ''); ?>><?php echo $entry_navdefault; ?></option>
                <option value="basecart_module_navinverse" <?php echo ($basecart_module_nav == 'basecart_module_navinverse' ? 'selected' : ''); ?>><?php echo $entry_navinverse; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-theme"><?php echo $entry_theme; ?></label>
            <div class="col-sm-10">
              <select name="basecart_module_theme" id="input-theme" class="form-control">
                <option value="basecart_module_themedefault" <?php echo ($basecart_module_theme == 'basecart_module_themedefault' ? 'selected' : ''); ?>><?php echo $entry_themedefault; ?></option>
                <option value="basecart_module_themecerulean" <?php echo ($basecart_module_theme == 'basecart_module_themecerulean' ? 'selected' : ''); ?>><?php echo $entry_themecerulean; ?></option>
                <option value="basecart_module_themecosmo" <?php echo ($basecart_module_theme == 'basecart_module_themecosmo' ? 'selected' : ''); ?>><?php echo $entry_themecosmo; ?></option>
                <option value="basecart_module_themecyborg" <?php echo ($basecart_module_theme == 'basecart_module_themecyborg' ? 'selected' : ''); ?>><?php echo $entry_themecyborg; ?></option>
                <option value="basecart_module_themedarkly" <?php echo ($basecart_module_theme == 'basecart_module_themedarkly' ? 'selected' : ''); ?>><?php echo $entry_themedarkly; ?></option>
                <option value="basecart_module_themeflatly" <?php echo ($basecart_module_theme == 'basecart_module_themeflatly' ? 'selected' : ''); ?>><?php echo $entry_themeflatly; ?></option>
                <option value="basecart_module_themejournal" <?php echo ($basecart_module_theme == 'basecart_module_themejournal' ? 'selected' : ''); ?>><?php echo $entry_themejournal; ?></option>
                <option value="basecart_module_themelumen" <?php echo ($basecart_module_theme == 'basecart_module_themelumen' ? 'selected' : ''); ?>><?php echo $entry_themelumen; ?></option>
                <option value="basecart_module_themepaper" <?php echo ($basecart_module_theme == 'basecart_module_themepaper' ? 'selected' : ''); ?>><?php echo $entry_themepaper; ?></option>
                <option value="basecart_module_themereadable" <?php echo ($basecart_module_theme == 'basecart_module_themereadable' ? 'selected' : ''); ?>><?php echo $entry_themereadable; ?></option>
                <option value="basecart_module_themesandstone" <?php echo ($basecart_module_theme == 'basecart_module_themesandstone' ? 'selected' : ''); ?>><?php echo $entry_themesandstone; ?></option>
                <option value="basecart_module_themesimplex" <?php echo ($basecart_module_theme == 'basecart_module_themesimplex' ? 'selected' : ''); ?>><?php echo $entry_themesimplex; ?></option>
                <option value="basecart_module_themeslate" <?php echo ($basecart_module_theme == 'basecart_module_themeslate' ? 'selected' : ''); ?>><?php echo $entry_themeslate; ?></option>
                <option value="basecart_module_themespacelab" <?php echo ($basecart_module_theme == 'basecart_module_themespacelab' ? 'selected' : ''); ?>><?php echo $entry_themespacelab; ?></option>
                <option value="basecart_module_themesuperhero" <?php echo ($basecart_module_theme == 'basecart_module_themesuperhero' ? 'selected' : ''); ?>><?php echo $entry_themesuperhero; ?></option>
                <option value="basecart_module_themeunited" <?php echo ($basecart_module_theme == 'basecart_module_themeunited' ? 'selected' : ''); ?>><?php echo $entry_themeunited; ?></option>
                <option value="basecart_module_themeyeti" <?php echo ($basecart_module_theme == 'basecart_module_themeyeti' ? 'selected' : ''); ?>><?php echo $entry_themeyeti; ?></option>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
