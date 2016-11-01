<?php echo $header; ?>
    <div class="container">
        <div class="row"><?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
                <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
                <?php $class = 'col-sm-9'; ?>
            <?php } else { ?>
                <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <h2><?php echo $heading_title; ?></h2>
                <?php if ($thumb || $description) { ?>
                    <div class="row">
                        <?php if ($thumb) { ?>
                            <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail"/></div>
                        <?php } ?>
                        <?php if (strlen($description) > 15) { ?>
                            <div class="col-sm-10"><?php echo $description; ?></div>
                            <hr>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if ($categories) { ?>
                    <h3><?php echo $text_refine; ?></h3>
                    <?php if (count($categories) <= 5) { ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="list-group">
                                    <?php foreach ($categories as $category) { ?>
                                        <a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
                                <div class="col-sm-3">
                                    <div class="list-group">
                                        <?php foreach ($categories as $category) { ?>
                                            <a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if ($products) { ?>

                    <!-- <p><a href="<?php echo $compare; ?>" id="compare-total" class="btn btn-primary"><?php echo $text_compare; ?></a></p> -->
                    <div class="row">
                        <div class="col-md-4 hidden-xs">
                            <div class="btn-group ">
                                <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
                                <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
                            </div>
                        </div>

                        <div class="col-md-3 text-right hidden-xs">
                            <select id="input-sort" class="form-control" onchange="location = this.value;">
                                <?php foreach ($sorts as $sorts) { ?>
                                    <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                                        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-2 text-right hidden-xs">
                            <select id="input-limit" class="form-control" onchange="location = this.value;">
                                <?php foreach ($limits as $limits) { ?>
                                    <?php if ($limits['value'] == $limit) { ?>
                                        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <?php foreach ($products as $product) { ?>
                            <div class="product-layout product-list col-xs-12">
                                <div class="product-thumb">
                                    <div class="image">
                                        <a href="<?php echo $product['href']; ?>">
                                            <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"
                                                 class="img-responsive"/>
                                        </a>
                                    </div>
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

                                            <?php if ($product['price']) { ?>


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
                                                        <span class="price regular list"><?php echo $product['price']; ?></span>
                                                    <?php } else { ?>
                                                        <span class="price regular old list mr1rem"><?php echo $product['price']; ?></span>
                                                        <span class="price special list"><span><?php echo $product['special']; ?></span></span>
                                                    <?php } ?>
                                                </div>

                                            <?php } ?>
                                        </div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success"
                                                    onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><?php echo $button_cart; ?></button>

                                            <!-- <button type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
                                                <i class="fa fa-heart"></i></button>
                                            -->

                                            <button type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i
                                                    class="fa fa-bar-chart"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                    </div>
                <?php } ?>
                <?php if (!$categories && !$products) { ?>
                    <p><?php echo $text_empty; ?></p>
                    <div class="buttons">
                        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
                    </div>
                <?php } ?>
                <?php echo $content_bottom; ?></div>
            <?php echo $column_right; ?></div>
    </div>
<?php echo $footer; ?>