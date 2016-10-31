<?php if (false) { ?>

<div class="container">
    <footer>
        <div class="row">

            <?php if ($informations) { ?>
            <div class="col-sm-3">
                <h4><?php echo $text_information; ?></h4>
                <ul class="list-group">
                    <?php foreach ($informations as $information) { ?>
                    <li class="list-group-item"><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>

            <div class="col-sm-3">
                <h4><?php echo $text_service; ?></h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
                </ul>
            </div>

            <div class="col-sm-3">
                <h4><?php echo $text_extra; ?></h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
                </ul>
            </div>

            <div class="col-sm-3">
                <h4><?php echo $text_account; ?></h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
                </ul>
            </div>

        </div>

    </footer>
</div>

<?php } ?>

<?php
    $whitelist = array('127.0.0.1', '::1' );

    if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
        $local = TRUE;
    } else {
        $local = FALSE;
    }
?>

<?php if (!$local) { ?>
    <script>
        (function (i, s, o, g, r, a, m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)}, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
        })
        (window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-3389661-51', 'auto');
        ga('send', 'pageview');

    </script>

<?php } ?>

</body></html>

<?php if ($local) { ?>
    <div class="bootSizes">
        <div class="displaySize only-xs">XS</div>
        <div class="displaySize only-sm">SM</div>
        <div class="displaySize only-md">MD</div>
        <div class="displaySize only-lg">LG</div>
        <div class="displaySize only-xl">XL</div>
    </div>
<?php } ?>
