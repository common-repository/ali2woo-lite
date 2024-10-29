<div class="modal-overlay modal-override-product">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><?php  esc_html_e('Select an overriding product', 'ali2woo');?></h3>
            <a class="modal-btn-close" href="#"></a>
        </div>
        <div class="modal-body">
            <div class="modal-override-product-loader a2wl-load-container" style="padding:80px 0;"><div class="a2wl-load-speeding-wheel"></div></div>
            <div class="modal-override-product-content">
                <div class="override-option" style="display:none">
                  <input type="checkbox" id="a2wl-change-product-supplier" class="form-control">
                  <label for="a2wl-change-product-supplier">
                    <strong><?php  esc_html_e('Change product supplier', 'ali2woo');?></strong>
                    <div>If you select this option, all old variations will be kept and we will suggest that you change the supplier for each of the existing variations.</div>
                  </label>
                </div>
                <div class="a2wl-warning" id="a2wl-override-warning">Override will DELETE ALL of your existing product variants and replace them with variants from a new supplier.</div>
                <div class="override-error"></div>
                <div class="override-items">
                  <div class="override-item">
                    <div class="item-title" style="font-weight: bold;">Existing product</div>
                    <div class="item-body" style="font-weigth:bold">
                      <div class="input-block">
                        <select id="a2wl-override-select-products" style="width:100%" class="form-control" data-placeholder="<?php  esc_html_e('Search products', 'ali2woo');?>"></select>
                      </div>
                    </div>
                  </div>
                  <div class="a2wl-icon-arrow-right override-delimiter"></div>
                  <div class="override-item">
                    <div class="item-title">Override with</div>
                    <div class="item-body override-with">

                    </div>
                  </div>
                </div>
                <div class="override-options" style="display:none">
                  <div class="override-option">
                    <input type="checkbox" id="a2wl-override-title-description" class="form-control">
                    <label for="a2wl-override-title-description">
                      <strong><?php  esc_html_e('Override Title and Description', 'ali2woo');?></strong>
                      <div>If you select this option, we will replace your existing product title and description with the title/description from the overriding product.</div>
                    </label>
                  </div>
                  <div class="override-option">
                    <input type="checkbox" id="a2wl-override-images" class="form-control">
                    <label for="a2wl-override-images">
                      <strong><?php  esc_html_e('Override Images', 'ali2woo');?></strong>
                      <div>If you select this option, we will DELETE ALL of your existing product images and will replace them with the images from the overriding product.</div>
                    </label>
                  </div>
                </div>
                <div class="override-order-variations" style="display:none">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default modal-close" type="button"><?php  esc_html_e('Cancel', 'ali2woo');?></button>
            <button class="btn btn-success btn-icon-left do-override-product" type="button">
              <span class="title"><?php  esc_html_e('Override', 'ali2woo');?></span>
              <span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span>
              <div class="btn-loader-wrap"><div class="a2wl-loader"></div></div>
            </button>
        </div>
    </div>
</div>

