<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>"/>
    <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>"/>
    <?php } ?>
    <?php if ($keywords) { ?>
        <meta name="keywords" content="<?php echo $keywords; ?>"/>
    <?php } ?>
    <!-- <link href="catalog/view/theme/basecart/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="catalog/view/theme/basecart/css/style.css" rel="stylesheet">
    <link href="catalog/view/theme/basecart/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <link href="catalog/view/theme/basecart/css/main.css" rel="stylesheet">

    <script src="catalog/view/theme/basecart/js/jquery.min.js"></script>
    <!-- <script src="catalog/view/theme/basecart/js/bootstrap.min.js"></script> -->
    <script src="catalog/view/theme/basecart/lib/tether/dist/js/tether.min.js"></script>
    <script src="catalog/view/theme/basecart/lib/boostrap4/dist/js/bootstrap.min.js"></script>
    <script src="catalog/view/theme/basecart/js/common.js"></script>
    <?php foreach ($links as $link) { ?>
        <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>">
    <?php } ?>
    <?php foreach ($styles as $style) { ?>
        <link href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>">
    <?php } ?>
    <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>"></script>
    <?php } ?>
    <?php foreach ($analytics as $analytic) { ?>
        <?php echo $analytic; ?>
    <?php } ?>
</head>
<body class="<?php echo $class; ?>">
<header>
    <?php if ($nav == "basecart_module_navinverse") { ?>
        <?php $class = 'navbar-inverse'; ?>
    <?php } else { ?>
        <?php $class = 'navbar-default'; ?>
    <?php } ?>
    <nav class="navbar <?php echo $class; ?>">
        <div class="container">

            <div class="row">

                <div class="col-sm-3">
                    <?php if ($logo) { ?>
                        <a class="navbar-brand" href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive"/></a>
                    <?php } else { ?>
                        <a class="navbar-brand" href="<?php echo $home; ?>"><?php echo $name; ?></a>
                    <?php } ?>
                </div>

                <div class="col-sm-3 pt1rem">
                    <?php echo $search; ?>
                </div>

                <div class="col-sm-3 pt1rem">
                    <a href="tel:+19165095860" class="phonetop"><?php echo $telephone; ?></a>


                </div>
                <div class="col-sm-3 pt1rem">
                    <address>
                        <a class="mailtop" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br/>
                        <?php echo $address; ?>
                        <!-- <a href="/contact-us/">Map</a> -->
                    </address>
                </div>
            </div>


            <div class="collapse navbar-collapse navbar-ex1-collapse">

                <ul class="nav navbar-nav navbar-right">
                    <?php echo $cart; ?>
                    <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user n-icon"></i><span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php if ($logged) { ?>
                                <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                                <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                                <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
                                <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
                                <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
                            <?php } else { ?>
                                <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
                                <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
</header>


