<h3><?php echo $heading_title; ?></h3>
<div class="row">

    <?php foreach ($products as $product) { ?>


    <?php if (count($products) % 3 == 0) : ?>
    <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12" data-sr="">

        <?php elseif (count($products) % 2 == 0): ?>
        <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12" data-sr="">

            <?php endif; ?>
            <div class="product-thumb">
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"
                                                                                  class="img-responsive"/></a></div>
                <div>
                    <div class="caption">
                        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>

                        <?php if (strlen($product['description'])) { ?>
                            <p><?php echo $product['description']; ?></p>
                        <?php } ?>

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

                        <table class="table table-sm noFirstBorder">
                            <?php foreach ($product['attribute_groups'] as $attribute_group) {
                                if ($attribute_group['name'] == 'Rebates') {
                                    foreach ($attribute_group['attribute'] as $attribute) {

                                        if (trim($attribute['name']) == 'PG&E Rebate') {
                                            $rebateClass = 'pge';
                                        } elseif ($attribute['name'] == 'SMUD Rebate') {
                                            $rebateClass = 'smud';
                                        } else {
                                            $rebateClass = '';
                                        }
                                        ?>

                                        <tr>
                                            <td class="<?= $rebateClass; ?>"><?php echo $attribute['name'] ?></td>
                                            <td><?php echo $attribute['text']; ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </table>

                        <div class="priceBlock mb1rem">
                            <?php if (!$product['special']) { ?>
                                <span class="price regular feature"><?php echo $product['price']; ?></span>
                            <?php } else { ?>
                                <span class="price regular old feature mr1rem"><?php echo $product['price']; ?></span>
                                <span class="price special feature"><span><?php echo $product['special']; ?></span></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success" onclick="cart.add('<?php echo $product['product_id']; ?>');"><?php echo $button_cart; ?></button>
                        <!-- <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button> -->
                        <!-- <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-bar-chart"></i></button> -->
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
