=== AliExpress Dropshipping Plugin for WooCommerce – AliNext ===
Contributors: ali2woo
Tags: aliexpress dropshipping, woocommerce dropshipping, dropship
Requires at least: 5.9
Tested up to: 6.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Stable tag: trunk
Requires PHP: 8.0
WC tested up to: 9.3
WC requires at least: 5.0

This AliExpress Dropshipping Plugin for WooCommerce: Import products with reviews from AliExpress and fulfill orders automatically! It's integrated with the AliExpress Affiliate Program, allowing you to earn more by selling affiliate products.

== Description ==

This AliExpress Dropshipping Plugin for WooCommerce: Import products with reviews from AliExpress and fulfill orders automatically! It's integrated with the AliExpress Affiliate Program, allowing you to earn more by selling affiliate products.

[Knowledge Base](https://ali2woo.com/codex/) | [Chrome extension](https://ali2woo.com/codex/aliexpress-dropshipping-chrome-extension/) | [AliNext official website](https://ali2woo.com/) | [Full Version](https://ali2woo.com/dropshipping-plugin/)

###How To Import Products from AliExpress using the Chrome extension?
[youtube https://youtu.be/Lbq-_3j4vwk]

###How To Use Global Pricing Rules?
[youtube https://youtu.be/N-GZ3EpJYiw]

###How To Fulfill AliExpress Orders in Bulk?
[youtube https://youtu.be/S5368Pvo_F0]

### Compatibility with Woocommerce HPOS
Starting from version 3.1.3, AliNext is compatible with HPOS. 
To activate HPOS, follow these steps: go to Woocommerce -> Settings -> Advanced -> Order data storage and select the "High-performance order storage (recommended)" option. Additionally, ensure that you have enabled the "Enable compatibility mode (synchronizes orders to the posts table)" option. Save your Woocommerce settings and confirm that all orders are synchronized by Woocommerce. If some orders are pending synchronization, you will see information about it there. Please wait until all orders are synchronized before using the AliNext plugin normally. For further information about HPOS [refer official woocommerce article](https://woocommerce.com/posts/platform-update-high-performance-order-storage-for-woocommerce/)

### Important Notice:

- Plugin works based on WooCommerce plugin.

- You have to generate the access token (AliExpress token) to place or sync orders. You don't need the token to import products. Please [follow our instruction](https://help.ali2woo.com/codex/how-to-get-access-token-from-aliexpress/) in order to generate token.

- Your permalink structure must NOT be "Plain"

- It is released on WordPress.org and you can use plugin as free to build themes for sale.

### FEATURES
  
&#9658; **Import Products**:

This AliExpress Dropshipping plugin imports products into WooCommerce using a built-in search module or a free Chrome extension. Additionally, it can pull products from selected categories or store pages on AliExpress. Also, if you want to import a specific product only, you can use AliExpress product ID or AliExpress product URL to do that.

- **Import from single product page**

- **Import from category page**

- **Import from store page**

&#9658; **Import All Products of specific AliExpress seller or store**:

The plugin has a separate page allowing you to search for products in specific AliExpress store or seller. Often it helps if you find some store on AliExpress and want to pull all items form the store.

See a detailed guide on this topic [HERE.](https://help.ali2woo.com/codex/how-to-import-all-products-from-the-store/)

&#9658; **Load product categories from AliExpress**:

The plugin has a feature to import product categories from AliExpress. It saves you time from having to build the category tree yourself. When the plugin imports the categories, it automatically assigns them to the appropriate imported products.

For further information, please check our [instruction.](https://help.ali2woo.com/alinext-kb/how-to-set-category-for-imported-products/)


&#9658; **Split product variants into separate products**:

The plugin allows splitting product variants. For example: a lot of products on AliExpress come with the "ShipFrom" attribute. Often drop shippers don't want to show this variant for customers. With this feature it's possible to split such a product by the "ShipFrom" attribute. As result you will get separate products without it.

Please look through the [this article](https://ali2woo.com/codex/how-to-split-product-variants-video/) to understand clearly how splitting feature works.

&#9658; **Remove "Ship From" attribute automatically**: save your time, you don’t need to edit the "Shipping From" attribute for each product one by one, AliNext will do that automatically for you!

Check out an article from the plugin Knowledge Base to know [how to use this feature.](https://help.ali2woo.com/codex/how-to-hide-the-ship-from-attribute-from-product-page/)

&#9658; **Override product supplier**:

The product override feature is very helpful if you get a new order for the out-of-stock product and want to fulfill the order from another supplier or vendor on AliExpress.
Also it helps if you have a product that was loaded through other dropshipping tool or it was added manually in WooCommerce and you want to connect the product to some AliExpress item using AliNext

Check out an article from the plugin Knowledge Base to know [how to use this feature.](https://ali2woo.com/codex/how-to-change-the-product-supplier/)

&#9658; **Change product images through the built-in image editor**:

Have you ever noticed the most product images having seller’s logo on AliExpress? And when you import the products into your store, those watermarks are visible for your customers. We know about such a problem and added a built-in images editor to the plugin`s features. The image tool allows to adjust photos right in your WordPress Dashboard.

It's recommended to check a detailed article [about the image editor tool.](https://ali2woo.com/codex/how-to-hide-watermark-on-images-from-aliexpress/)

&#9658; **Configure settings for all imported products**:

This set of settings apply to all products imported from AliExpress. Go to AliNext Settings > Common Settings > Import Settings.

- **Language**: Set language of product data such as title, description, variations, attributes, etc. Our plugin supports all languages which available on AliExpress.

- **Currency**: Change the currency of products. AliNext supports all currencies which AliExpress portal operates with. 

- **Default product type**: By default the plugin imports product as "Simple/Variable product". In this case, shoppers will stay on your website when they make a purchase else choose the "External/Affiliate Product" option and your visitors will be redirected to AliExpress to finish the purchase.

- **Default product status**: Choose the "Draft" option and imported products will not be visible on your website frontend.

- **Not import specifications**: Turn this feature on if you'd NOT like to import product attributes from AliExpress. You can see these attributes in the "specifications" tab on the AliExpress website.

- **Not import description**: Enable this feature if you don't want to import product description from AliExpress.

- **Don't import images from the description**: If you want to skip images from the product description and don't import them to the WordPress media library, use this option.

- **Use external image URLs**: By default, the plugin keeps product images on your server. If you want to save free space on your server, 
activate this option and the plugin will load an image using an external AliExpress URL. Please note: This feature works if the plugin is active only!

- **Use random stock value**: By default the plugin imports the original stock level value. Some sellers on AliExpress set very high value and it doesn't look natural. To solve the issue just enable the feature. It forces the plugin to generate stock level value automatically and choose it from a predefined range.

- **Import in the background**: Enable this feature and allow the plugin to import products in a background mode. In this case, each product is loaded in several stages. First, the plugin imports main product data such as: title, description, and attributes, and in the second stage, it imports product images and variants. This feature speeds up the import process extremely. Please note: In the first stage a product is published with the "draft" status and then when all product data is loaded the product status is changed to "published".

- **Allow product duplication**: Allow the import of an already imported product. This can be useful when you want to override a product with the same product.

- **Convert case of attributes and their values**: Products may come with different text case of attributes and their values. Enable this feature to covert all texts to the same case.

- **Remove "Ship From" attribute**: Use this feature to remove the "Ship From" attribute automatically during product import.

&#9658; **Set options related to the order fulfillment process**:

These settings allow changing an order status after the order is placed on AliExpress. Go to AliNext Settings > Common Settings > Order Fulfillment Settings.

- **Delivered Order Status**: Change order status when all order items have been delivered.

- **Shipped Order Status**: Change order status when all order items have been shipped.

- **Placed Order Status**: Change order status when order is placed. 

- **Default shipping method**: If possible, the extension auto-select the shipping method on AliExpress during an order fulfillment process.

- **Override phone number**: The extension will use these phone code and number instead of the real phone provided by your customer.

- **Custom note**: Set a note for a supplier on the AliExpress.

- **Transliteration**: Enable the auto-transliteration of AliExpress order details such as first name, last name, address, etc.

- **Middle name field**: Adds the Middle name field to WooCommerce checkout page in your store. The extension uses the field data during an order-fulfillment process on AliExpress.

&#9658; **Earn more with AliExpress Affiliate program**:

On this setting page, you can connect your store to AliExpress Affiliate program. You can connect to the program using your AliExpress, Admitad, or EPN account.
Go to AliNext Settings > Account Settings.

We have a detailed guide on how to connect to the AliExpress Affiliate program [HERE](https://ali2woo.com/codex/account-settings/) 

&#9658; **Set up global pricing rules for all products**:

These options allow setting markup over AliExpress prices. You can add separate markup formula for each pricing range. The formula is a rule of a price calculation that includes different math operators such as +, *, =. Pricing rules support three different modes that manage the calculation in your formulas. Additionally, you can add cents to your prices automatically. And even more, it's easy to apply your pricing rules to already imported products. 

Go to AliNext Settings > Pricing Rules.

Also, read a detailed post about [global pricing rules.](https://ali2woo.com/codex/pricing-markup-formula/)

&#9658; **Filter or delete unnecessary text from AliExpress product**: 

Here you can filter all unwanted phrases and text from AliExpress product. It allows adding unlimited rules to filter the texts. These rules apply to the product title, description, attributes, and reviews. Please note the plugin checks your text in case-sensitive mode.

Go to AliNext Settings > Phrase Filtering.

See a detailed guide on this topic [HERE.](https://ali2woo.com/codex/phrase-filtering/)

&#9658; **Import reviews from AliExpress**: 

Import product reviews quickly from AliExpress, skip unwanted reviews, import translated version of the reviews or reviews related to a paticular country.

Go to AliNext Settings > Reviews settings.

Check out a detailed guide about [reviews settings.](https://ali2woo.com/codex/importing-reviews/)

Please note: in the lite plugin version all reviews options are available except "Import more reviews automatically". That option is available in the Pro version (read below please).

&#9658; **Add Shipping cost to your pricing markup**:

Use this feature to increase your margin by including shipping cost to the product price. 

Go to AliNext Settings > Pricing Rules > Add shipping cost.

See a detailed guide on this topic [HERE.](https://ali2woo.com/codex/pricing-markup-formula/#shipping)

&#9658; **Automatically place orders on AliExpress through the AliExpress API**:

Go to AliNext Settings > Account Settings and click "Get Access Token". AliExpress portal will ask your permission to place orders via API. Accept it and then the feature will be activated! Go to your Woocomemrce orders list page and try to place your objects, you will see that now you can place it via API very fast.

See a detailed guide on this topic [HERE.](https://help.ali2woo.com/codex/fulfill-orders-using-aliexpress-api/)

Please note: In the lite plugin version you have a limit of 20 orders operations per day. So you can place or sync 20 orders max per day. You can increase limit using Pro version.

&#9658; **Sync orders with AliExpress through the AliExpress API**:
This feature allow you to update shipping tracking information automatically.
Please note: In the lite plugin version you have a limit of 10 orders operations per day. So you can place or sync 10 orders max per day. You can increase limit using Pro version.


###PRO VERSION

- **All features from the free version**

- **6 months of Premium support**

- **Lifetime update**

&#9658; **Find best products using built-in search tool**: 

**[pro version feature]** Get more search requests to find the best products for your store. In contrast to AliNext Lite allowing you to make only 100 operations per day.

&#9658; **Find all products of the specific store or seller on AliExpress**: 

**[pro version feature]** Get more search requests to find the best products of specific AliExpress store or seller. In contrast to AliNext Lite allowing you to make only 100 operations per day.

&#9658; **Load whole category tree from AliExpress**: 

**[pro version feature]** Get more category requests to load whole category tree from AliExpress. In contrast to AliNext Lite allowing you to make only 5 such requests per day.

&#9658; **Fast Order fulfillment using API**: 

**[pro version feature]** Place more orders on AliExpress through the AliExpress API. In contrast to AliNext Lite allowing you to place ONLY 10 orders using the API.

&#9658; **Sync Orders using API**: 

**[pro version feature]** Sync more orders with AliExpress through the AliExpress API. In contrast to AliNext Lite allowing you to sync ONLY 10 orders using the API.

&#9658; **Set options related to the product synchronization**:

**[pro version feature]** This set of features allows synchronizing an imported product automatically with AliExpress. Also, you can set a specific action that applies to the product depending on change occurring on AliExpress.  Go to AliNext Settings > Common Settings > Schedule Settings.

- **Aliexpress Sync**: Enable product sync with AliExpress in your store. It can sync product price, quantity and variants.

- **When product is no longer available**: Choose an action when some imported product is no longer available on AliExpress.

- **When variant is no longer available**: Choose an action when some product variant becomes not available on AliExpress.

- **When a new variant has appeared**: Choose an action when a new product variant appears on AliExpress.

- **When the price changes**: Choose an action when the price of some imported product changes on AliExpress.

- **When inventory changes**: Choose an action when the inventory level of some imported product changes on AliExress.


&#9658; **Get email alerts on product changes**:

**[pro version feature]** Get email notification if product price, stock or availability change, also be alerted if new product variations appear on AliExpress.

You can set email address for notifications and override the email template if needed. The plugin sends notification once per half-hour.

&#9658; **Sync reviews from AliExpress**: 

**[pro version feature]** Check for an appearance of new reviews in all imported products. Unlock "Import more reviews automatically" option in the review settings.

&#9658; **Import shipping methods from AliExpress and/or show shipping selection on your website frontend**: 

**[pro version feature]** Easily import delivery methods from AliExpress, set pricing rules to add your own margin over the original shipping cost, show shipping methods selection on the product page, cart page, checkout page.

Go to AliNext Settings > Shipping settings.

See a detailed guide on this topic [HERE.](https://ali2woo.com/codex/shipping-settings/)

[GET PRO VERSION](https://ali2woo.com/dropshipping-plugin/) or [https://ali2woo.com/dropshipping-plugin/](https://ali2woo.com/dropshipping-plugin/)

### Documentation

- [Getting Started](https://ali2woo.com/codex/)

= Minimum Requirements =

* PHP 8.0 or greater is recommended
* MySQL version 5.0 or greater
* WooCommerce 5.0.0+

= Support = 

In case you have any questions or need technical assistance, get in touch with us through our [support center](https://support.ali2woo.com).


= Follow Us =

* The [AliNext Plugin](https://ali2woo.com/) official homepage.
* Follow AliNext on [Facebook](https://facebook.com/ali2woo) & [Twitter](https://twitter.com/ali2woo).
* Watch AliNext training videos on [YouTube channel](https://www.youtube.com/channel/UCmcs_NMPkHi0IE_x9UENsoA)
* Other AliNext social pages: [Pinterest](https://www.pinterest.ru/ali2woo/), [Instagram](https://www.instagram.com/ali2woo/), [LinkedIn](https://www.linkedin.com/company/18910479)

== Installation ==

= From within WordPress =

1. Visit 'Plugins > Add New'
2. Search for 'AliNext Lite'
3. Activate AliNext Lite from your Plugins page.
4. Go to "after activation" below.

= Manually =

1. Upload the `alinext-lite` folder to the `/wp-content/plugins/` directory
2. Activate the Yoast SEO plugin through the 'Plugins' menu in WordPress
3. Go to "after activation" below.

== Screenshots ==

1. The AliNext Lite dropshipping plugin build-in product search tool. 
2. The Import List page, here you can adjust the products before pushing them into WooCommerce store.
3. Built-in image editor tool, easy way to remove supplier logos for the images.
4. The AliNext Lite Setting page.
5. Set up your pricing markups.
6. Remove or replace unwanted text from the content imported from AliExpress
8. Feature to quick search for all products of the same seller/store from AliExpress

== Changelog ==
= 3.4.5 - 2024.10.07 =
* Enhance settings transfer module
* Fix minor bugs

= 3.4.4 - 2024.09.30 =
* Add compatibility with WooCommerce 9.3.* 
* Add server max execution time and memory limit checks on the System Info page
* Enhance product background loader (should work faster even on cheap hosting plans)
* Fix shipping loader (premium plugin version)
* Fix built-in store products` search
* Fix issue causing Wordfence notice 

= 3.4.3 - 2024.08.26 =
* Fix bug pricing rules type is not applied on choosing pricing set;
* Add compatibility with WooCommerce 9.2.*  

= 3.4.0 - 2024.08.01 =
* Enhanced pricing rules module: ability to add a category to pricing rule (if category field is empty, then rule will apply to any category);
* Enhanced pricing rules module: ability to create Pricing rule sets. Set is a group of pricing rules and settings, you can create few sets and switch between then during sale. Don't forget to use Apply pricing to exiting products feature after you switch to another pricing rule set;
* Fix image editor bug;
* Refactor legacy code and fix minor bugs; 

= 3.3.9 - 2024.07.08 =
* Enhanced plugin security by adding WordPress nonce to all Ajax methods
* Improved plugin security by checking user role in all plugin methods;
* Enhanced plugin security by escaping HTML input in template views;
* Improved plugin security by escaping SQL queries;
* Enhanced order fulfillment module to synchronize product shipping information when refreshing in the fulfillment popup;
* Fixed minor bugs and improve code style;

= 3.3.5 - 2024.06.13 =
* Refactor import products from CSV.
* Enhance order fulfillment module; Now order is placed with country currency.
* Fix minor bugs.

= 3.3.3 - 2024.05.28 =
* Fix plugin activation bug;

= 3.3.2 - 2024.05.27 =
* Update background process library, add namespaces for the library;
* Fix bug with product last-update date;
* Add cancel button for all background processed;
* Add php max_execution_time check to system info page
* Fix minor bug, old code base refactor;

= 3.3.0 - 2024.05.20 =
* Improve bulk price application script; Now you can close or refresh page when start the process;
* Add status bar for all backroud processes in order to make it more clear;
* Fix bug with product descriprtion images;
* Fix minor bug, old code base refactor;

= 3.2.8 - 2024.05.03 =
* fix function saving images in image editor tool
* fix pricing rules bug in plugin backend
* fix product description loading
* update some plugin dependecies
* fix minor bugs

= 3.2.7 - 2024.04.10 =
* add image type file check to built-in image editor
* add feature to load AliExpress category tree for imported product
* replace || with OR in sql queries as this format is  deprectated
* fix minor bugs

= 3.2.6 - 2024.04.04 =
* fix built-in image editor
* fix chrome extension connection bug
* fix is-not-applied price bug
* fix minor bugs

= 3.2.4 - 2024.02.16 =
* fix few deprecated (legacy) methods in code
* remove old Requests library from the code and use native Requests library from wordpress core
* fix Woocommerce 8.6.* compatibility bug

= 3.2.1 - 2024.01.13 =
* fix chrome extension connection bug
* increase daily quota for order place and sync operations to 20 per day (for the lite plugin version)
== Upgrade Notice ==


 