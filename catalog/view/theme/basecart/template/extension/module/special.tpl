<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($products as $product) { ?>
  <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div>
        <div class="caption">
          <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
          <p><?php echo $product['description']; ?></p>
          <?php if ($product['rating']) { ?>
          <div class="rating">
            <?php for ($i = 1; $i <= 5; $i++) { ?>
            <?php if ($product['rating'] < $i) { ?>
            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
            <?php } else { ?>
            <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
            <?php } ?>
            <?php } ?>
          </div>
          <?php } ?>
          <?php if ($product['price']) { ?>
          <p class="price">
            <?php if (!$product['special']) { ?>
            <strong><?php echo $product['price']; ?></strong>
            <?php } else { ?>
            <p class="text-danger"><strong><?php echo $product['special']; ?></strong></p>
            <?php } ?>
          </p>
          <?php } ?>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default" onclick="cart.add('<?php echo $product['product_id']; ?>');"><?php echo $button_cart; ?></button>
          <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
          <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-bar-chart"></i></button>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
