<?php
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<h1><?php esc_html_e('Add-ons/Extensions', 'ali2woo'); ?></h1>
<div class="a2wl-content">
    <div class="a2wl-addons">

        <div class="container">
            <div class="row">
                <?php foreach ($addons['addons'] as $addon): ?>
                    <div class="col-lg-6 col-md-12 space-top">
                        <div class="a2wl-addon-block">
                            <div class="thumb">
                                <a href="<?php echo $addon['url']; ?>" target="_blank"><img src="<?php echo $addon['image_url']; ?>"/></a>
                            </div>
                            <div class="title">
                                <a href="<?php echo $addon['url']; ?>" target="_blank"><?php echo $addon['title']; ?></a>
                            </div>
                            <div class="description">
                                <?php echo $addon['description']; ?> <a href="<?php echo $addon['url']; ?>" target="_blank"><?php  esc_html_e('Read more...', 'ali2woo'); ?></a>
                            </div>
                            <div class="price">
                                <?php echo $addon['price']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>    
</div>