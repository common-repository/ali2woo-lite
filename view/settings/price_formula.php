<?php
/**
 * @var PriceFormula[]|array $formulas
 * @var PriceFormula $default_formula
 * @var array $categories
 * @var array $pricingRuleTypes
 * @var PriceFormulaSet[]|array $PriceFormulaSets
 * @var PriceFormulaSettingsRepository $PriceFormulaSettingsRepository
 */

use AliNext_Lite\AbstractController;
use AliNext_Lite\PriceFormula;
use AliNext_Lite\PriceFormulaSet;
use AliNext_Lite\PriceFormulaSettingsRepository;
use function AliNext_Lite\get_setting;
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
$a2wl_pricing_rules_type = $PriceFormulaSettingsRepository->getPricingRulesType();
$a2wl_use_extended_price_markup = $PriceFormulaSettingsRepository->getUseExtendedPriceMarkup();
$a2wl_use_compared_price_markup = $PriceFormulaSettingsRepository->getUseComparedPriceMarkup();
$a2w_local_currency = strtoupper(get_setting('local_currency'));
?>

<div class="global-pricing mt20">
    <div class="pricing-rule-set">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel__header-title"><?php echo esc_html_e('Pricing rule sets', 'ali2woo'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="grid grid_default">
                    <div class="price-formula-sets__dropdown grid__col vertical-align">
                        <select name="a2wl_pricing_rule_set" id="a2wl_pricing_rule_set" class="form-control small-input" >
                            <?php foreach ($PriceFormulaSets as $PriceFormulaSet): ?>
                                <option value="<?php echo $PriceFormulaSet->getName(); ?>">
                                    <?php echo $PriceFormulaSet->getName(); ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="grid__col vertical-align">
                        <input id="a2wl_choose_set" class="btn btn-default" type="button" value="<?php echo esc_html_x('Choose', 'price rule sets', 'ali2woo'); ?>">
                        <input id="a2wl_remove_set" class="btn btn-default" type="button" value="<?php echo esc_html_x('Remove', 'price rule sets', 'ali2woo'); ?>">
                        <input id="a2wl_new_set" class="btn btn-default" type="button" value="<?php echo esc_html_x('New', 'price rule sets', 'ali2woo'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="a2wl_pricing_rule_set_description" class="grey-color mb20">
                            <?php echo esc_html_e('Pricing rule sets enable you to quickly load and switch between saved pricing rules.', 'ali2woo'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pricing-rules-type">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel__header-title"><?php echo esc_html_e('Pricing rule modes', 'ali2woo'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group input-block no-margin">
                            <select name="a2wl_pricing_rules_type" id="a2wl_pricing_rules_type" class="form-control small-input" >
                                <?php foreach ($pricingRuleTypes as $prt): ?>
                                    <option value="<?php echo $prt['value']; ?>" <?php if ($a2wl_pricing_rules_type === $prt['value']): ?>selected="selected"<?php endif;?>><?php echo $prt['name']; ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="pricing_rules_type_description" class="grey-color mb20"><?php echo $pricingRuleTypes[$a2wl_pricing_rules_type]['description']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo esc_html_e('Global pricing rules', 'ali2woo'); ?></h3>
            <span class="pull-right">
            <a class="disabled" style="display: none;"><?php echo esc_html_e('You have unsaved changes', 'ali2woo'); ?></a>
            <a href="#" class="apply-pricing-rules btn"><?php echo esc_html_e('Apply pricing rules to existing products', 'ali2woo'); ?></a></span>
        </div>


        <div class="panel-body p20 border-bottom js-default-prices"  <?php if ($a2wl_use_extended_price_markup): ?>style="display: none;" <?php else: ?>style="display: block;"<?php endif;?>>

            <div class="grid grid_default grid_center pricing-rules pb20 border-bottom">

                <div class="pricing-rules__icon grid__col vertical-align">
                    <svg class="icon-pricechanged">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-pricechanged"></use>
                    </svg>

                </div>


                <div class="pricing-rules__label grid__col grid__col_10 vertical-align">
                    <h3><?php echo esc_html_e('Markup', 'ali2woo'); ?></h3>
                </div>
                <div class="pricing-rules__field grid__col vertical-align">
                    <div class="input-group price-dropdown-group">
                        <input type="text" sign="<?php echo $default_formula->getSign(); ?>" class="input-group__input form-control value" value="<?php echo $default_formula->getValue(); ?>">
                        <div class="input-group__input">
                            <button type="button" class="input-group__input-inner btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if ($default_formula->getSign() == '+'): ?><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?><?php endif;?>
                                <?php if ($default_formula->getSign() == '='): ?><?php echo esc_html_e('Custom Price', 'ali2woo'); ?><?php endif;?>
                                <?php if ($default_formula->getSign() == '*'): ?><?php echo esc_html_e('Multiplier', 'ali2woo'); ?><?php endif;?>  <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right sign">
                                <li data-sign = "+" <?php if ($default_formula->getSign() == '+'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?></a></li>
                                <li data-sign = "=" <?php if ($default_formula->getSign() == '='): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Custom Price', 'ali2woo'); ?></a></li>
                                <li data-sign = "*" <?php if ($default_formula->getSign() == '*'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Multiplier', 'ali2woo'); ?></a></li>
                            </ul>
                        </div><!-- /btn-group -->
                    </div>
                </div>
                <div class="pricing-rules__arrow grid__col vertical-align">
                    <svg class="icon-full-arrow-right">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-full-arrow-right"></use>
                    </svg>
                </div>
                <div class="pricing-rules__price-type  grid__col vertical-align">
                    <h3 style="width: 135px;"><?php echo esc_html_e('Product price', 'ali2woo'); ?></h3>
                </div>
                <div class="pricing-rules__question grid__col vertical-align">
                    <div class="info-box" data-placement="left" data-toggle="tooltip" data-title=""></div>
                </div>

            </div>

            <div class="grid grid_default grid_center pricing-rules row pt20 compared">
                <div class="pricing-rules__icon grid__col vertical-align">
                    <div class="price-rulle-toggle <?php if (!$a2wl_use_compared_price_markup): ?>price-rulle-toggle--disabled<?php endif;?>"></div>
                </div>

                <div class="grid__col vertical-align switch-col" <?php if ($a2wl_use_compared_price_markup): ?>style="display:none;"<?php endif;?>>
                    <span class="grey-color" style=""><?php echo esc_html_e('Set your compared at pricing rules', 'ali2woo'); ?></span>
                </div>
                <div class="pricing-rules__label grid__col vertical-align switch-col" <?php if (!$a2wl_use_compared_price_markup): ?>style="display:none;"<?php endif;?>>
                    <h3><?php echo esc_html_e('Regular price markup', 'ali2woo'); ?></h3>
                </div>
                <div class="pricing-rules__field grid__col col-md-3 vertical-align switch-col" <?php if (!$a2wl_use_compared_price_markup): ?>style="display:none;"<?php endif;?>>
                    <div class="input-group price-dropdown-group">

                        <input type="text" sign="<?php echo $default_formula->getComparedSign(); ?>" value="<?php echo $default_formula->getComparedValue(); ?>" class="input-group__input form-control compared_value">

                        <div class="input-group__input">
                            <button type="button" class="input-group__input-inner btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if ($default_formula->getComparedSign() == '+'): ?><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?><?php endif;?>
                                <?php if ($default_formula->getComparedSign() == '='): ?><?php echo esc_html_e('Custom Price', 'ali2woo'); ?><?php endif;?>
                                <?php if ($default_formula->getComparedSign() == '*'): ?><?php echo esc_html_e('Multiplier', 'ali2woo'); ?><?php endif;?>  <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right compared_sign">
                                <li data-sign = "+" <?php if ($default_formula->getComparedSign() == '+'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?></a></li>
                                <li data-sign = "=" <?php if ($default_formula->getComparedSign() == '='): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Custom Price', 'ali2woo'); ?></a></li>
                                <li data-sign = "*" <?php if ($default_formula->getComparedSign() == '*'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Multiplier', 'ali2woo'); ?></a></li>
                            </ul>
                        </div><!-- /btn-group -->
                    </div>
                </div>
                <div class="pricing-rules__arrow grid__col vertical-align switch-col" <?php if (!$a2wl_use_compared_price_markup): ?>style="display:none;"<?php endif;?>>
                    <svg class="icon-full-arrow-right">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-full-arrow-right"></use>
                    </svg>
                </div>
                <div class="pricing-rules__price-type grid__col vertical-align switch-col" <?php if (!$a2wl_use_compared_price_markup): ?>style="display:none;"<?php endif;?>>
                    <h3 style="width: 135px;"><?php echo esc_html_e('Regular price', 'ali2woo'); ?></h3>
                </div>
                <div class="pricing-rules__question grid__col vertical-align switch-col" <?php if (!$a2wl_use_compared_price_markup): ?>style="display:none;"<?php endif;?>>
                    <div class="info-box" data-toggle="tooltip" data-placement="left" data-title=""></div><span class="grey-color" style="display:none;"><?php echo esc_html_e('Set your compared at pricing rules', 'ali2woo'); ?></span>
                </div>

            </div>
        </div>
        <div class="p20 extended_prices">
            <div class="container-flex">
                <div class="price-rulle-toggle <?php if (!$a2wl_use_extended_price_markup): ?>price-rulle-toggle--disabled<?php endif;?>"></div>
                <div>
                    <h3 style="margin: 0; line-height: 24px;"><?php echo esc_html_e('Advanced pricing rules', 'ali2woo'); ?></h3>
                    <div class="grey-color"><?php echo esc_html_e('Set your product markup depending on cost ranges.', 'ali2woo'); ?></div>
                </div>
            </div>
            <div class="js-advanced-prices mt20" <?php if (!$a2wl_use_extended_price_markup): ?>style="display: none;" <?php else: ?>style="display: block;"<?php endif;?>>

                <div class="table-responsive">
                    <table class="border">
                        <thead>
                            <tr class="border-bottom">
                                <th class="category"><?php echo esc_html_e('Category', 'ali2woo'); ?></th>
                                <th class="cost-range" colspan="3"><?php echo esc_html_e('Cost range', 'ali2woo'); ?></th>
                                <th class="markup"><?php echo esc_html_e('Markup', 'ali2woo'); ?></th>
                                <th class="compared-markup">
                                    <input class="use_compared_price_markup" type="checkbox" <?php if ($a2wl_use_compared_price_markup): ?>checked="checked"<?php endif;?>>
                                    <span><?php echo esc_html_e('Regular price markup', 'ali2woo'); ?></span>
                                </th>
                                <th class="action">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($formulas as $ind => $PriceFormula): ?>
                                <tr class="border-bottom">
                                    <td>
                                        <div class="field__input-wrap">
                                            <?php $selectedCategoryIds = $PriceFormula->getCategoryIds() ?? []; ?>
                                            <select class="form-control select2 categories" data-placeholder="<?php esc_html_e('Any category or choose', 'ali2woo');?>" multiple="multiple">
                                                <option></option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['term_id']; ?>"<?php if (in_array($category['term_id'], $selectedCategoryIds)): ?> selected="selected"<?php endif;?>>
                                                        <?php echo str_repeat('- ', $category['level'] - 1) . $category['name']; ?>
                                                    </option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="input-group__input form-control min_price" value="<?php echo $PriceFormula->getMinPrice(); ?>"><span class="input-group__input input-group__input_addon"> <?php echo $a2w_local_currency; ?> </span>
                                        </div>
                                    </td>
                                    <td>-</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="input-group__input form-control max_price" value="<?php echo $PriceFormula->getMaxPrice(); ?>"><span class="input-group__input input-group__input_addon"> <?php echo $a2w_local_currency; ?> </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group price-dropdown-group">
                                            <input type="text" sign="<?php echo $PriceFormula->getSign(); ?>" class="input-group__input form-control value" value="<?php echo $PriceFormula->getValue(); ?>">

                                            <div class="input-group__input">
                                                <button type="button" class="input-group__input-inner btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <?php if ($PriceFormula->getSign() == '+'): ?><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?><?php endif;?>
                                                    <?php if ($PriceFormula->getSign() == '='): ?><?php echo esc_html_e('Custom Price', 'ali2woo'); ?><?php endif;?>
                                                    <?php if ($PriceFormula->getSign() == '*'): ?><?php echo esc_html_e('Multiplier', 'ali2woo'); ?><?php endif;?>  <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right sign">
                                                    <li data-sign = "+" <?php if ($PriceFormula->getSign() == '+'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?></a></li>
                                                    <li data-sign = "=" <?php if ($PriceFormula->getSign() == '='): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Custom Price', 'ali2woo'); ?></a></li>
                                                    <li data-sign = "*" <?php if ($PriceFormula->getSign() == '*'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Multiplier', 'ali2woo'); ?></a></li>
                                                </ul>
                                            </div><!-- /btn-group -->

                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group price-dropdown-group <?php if (!$a2wl_use_compared_price_markup): ?>visually-hidden<?php endif;?>">
                                            <input type="text" sign="<?php echo $PriceFormula->getComparedSign(); ?>" class="input-group__input form-control compared_value" value="<?php echo $PriceFormula->getComparedValue(); ?>">

                                            <div class="input-group__input ">
                                                <button type="button" class="input-group__input-inner btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <?php if ($PriceFormula->getComparedSign() == '+'): ?><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?><?php endif;?>
                                                    <?php if ($PriceFormula->getComparedSign() == '='): ?><?php echo esc_html_e('Custom Price', 'ali2woo'); ?><?php endif;?>
                                                    <?php if ($PriceFormula->getComparedSign() == '*'): ?><?php echo esc_html_e('Multiplier', 'ali2woo'); ?><?php endif;?>  <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right compared_sign">
                                                    <li data-sign = "+" <?php if ($PriceFormula->getComparedSign() == '+'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?></a></li>
                                                    <li data-sign = "=" <?php if ($PriceFormula->getComparedSign() == '='): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Custom Price', 'ali2woo'); ?></a></li>
                                                    <li data-sign = "*" <?php if ($PriceFormula->getComparedSign() == '*'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Multiplier', 'ali2woo'); ?></a></li>
                                                </ul>
                                            </div><!-- /btn-group -->

                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn--transparent delete" <?php if ($ind === count($formulas) - 1): ?> style="display:none;" <?php endif;?>>
                                            <svg class="icon-cross">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-cross"></use>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                        <tfoot>
                            <tr class="border-bottom">
                                <td colspan="3">
                                    <div class="container-flex jc-sb panel__message">
                                        <div><?php echo esc_html_e('Rest of the price ranges', 'ali2woo'); ?></div>
                                        <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_e('These markups will be used for the rest of the price ranges', 'ali2woo'); ?>"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group price-dropdown-group">
                                        <input type="text" sign="<?php echo $default_formula->getSign(); ?>" class="input-group__input form-control default_value" value="<?php echo $default_formula->getValue(); ?>">

                                        <div class="input-group__input">
                                            <button type="button" class="input-group__input-inner btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php if ($default_formula->getSign() == '+'): ?><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?><?php endif;?>
                                                <?php if ($default_formula->getSign() == '='): ?><?php echo esc_html_e('Custom Price', 'ali2woo'); ?><?php endif;?>
                                                <?php if ($default_formula->getSign() == '*'): ?><?php echo esc_html_e('Multiplier', 'ali2woo'); ?><?php endif;?>  <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right default_sign">
                                                <li data-sign = "+" <?php if ($default_formula->getSign() == '+'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?></a></li>
                                                <li data-sign = "=" <?php if ($default_formula->getSign() == '='): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Custom Price', 'ali2woo'); ?></a></li>
                                                <li data-sign = "*" <?php if ($default_formula->getSign() == '*'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Multiplier', 'ali2woo'); ?></a></li>
                                            </ul>
                                        </div><!-- /btn-group -->
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group price-dropdown-group <?php if (!$a2wl_use_compared_price_markup): ?>visually-hidden<?php endif;?>">
                                        <input type="text" sign="<?php echo $default_formula->getComparedSign(); ?>" value="<?php echo $default_formula->getComparedValue(); ?>" class="input-group__input form-control default_compared_value">

                                        <div class="input-group__input">
                                            <button type="button" class="input-group__input-inner btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php if ($default_formula->getComparedSign() == '+'): ?><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?><?php endif;?>
                                                <?php if ($default_formula->getComparedSign() == '='): ?><?php echo esc_html_e('Custom Price', 'ali2woo'); ?><?php endif;?>
                                                <?php if ($default_formula->getComparedSign() == '*'): ?><?php echo esc_html_e('Multiplier', 'ali2woo'); ?><?php endif;?>  <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right default_compared_sign">
                                                <li data-sign = "+" <?php if ($default_formula->getComparedSign() == '+'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Fixed Markup', 'ali2woo'); ?></a></li>
                                                <li data-sign = "=" <?php if ($default_formula->getComparedSign() == '='): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Custom Price', 'ali2woo'); ?></a></li>
                                                <li data-sign = "*" <?php if ($default_formula->getComparedSign() == '*'): ?>style="display: none;"<?php endif;?>><a><?php echo esc_html_e('Multiplier', 'ali2woo'); ?></a></li>
                                            </ul>
                                        </div><!-- /btn-group -->
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="panel small-padding margin-small-top panel-danger" style="display: none;">
                    <div class="panel-body">
                        <div class="container-flex flex-between">
                            <div class="container-flex">
                                <div class="svg-container no-shrink">
                                    <svg class="icon-danger-circle margin-small-right">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-danger-circle"></use>
                                    </svg>
                                </div>
                                <div class="ml5 mr10">
                                    <div class="content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="assign-cents">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel__header-title"><?php echo esc_html_e('Assign cents', 'ali2woo'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="grey-color mb20"><?php echo esc_html_e('You can set a specific cent value for your retail price. We will use this value when forming the final price for your items (e.g., if you want the cost of your product to be XX.99 then add 99 to the fields below).', 'ali2woo'); ?></div>
                    </div>
                </div>
                <div class="grid grid_default grid_6">
                    <div class="grid__col">
                        <div class="field field_default">
                            <div class="field__label">
                                <input type="checkbox" id="cb-set-cents" <?php if ($cents > -1): ?> checked <?php endif;?>>
                                <label for="cb-set-cents"><?php echo esc_html_e('Assign cents', 'ali2woo'); ?></label>
                            </div>
                            <div class="field__input-wrap">
                                <input type="text" class="field__input form-control small-input" id="set-cents"  <?php if ($cents > -1): ?> value="<?php echo $cents; ?>" <?php else: ?> disabled="" <?php endif;?>>
                            </div>
                        </div>
                    </div>
                    <div class="grid__col">
                        <div class="field field_default input-wrap" <?php if (!$a2wl_use_compared_price_markup): ?>style="display:none;"<?php endif;?>>
                            <div class="field__label">
                                <input type="checkbox" id="cb-compared-set-cents" <?php if ($compared_cents > -1): ?> checked <?php endif;?>>
                                <label for="cb-compared-set-cents"><?php echo esc_html_e('Assign compared at cents', 'ali2woo'); ?></label>
                            </div>
                            <div class="field__input-wrap">
                                <input type="text" class="form-control small-input" id="compared-set-cents" <?php if ($compared_cents > -1): ?> value="<?php echo $compared_cents; ?>" <?php else: ?> disabled="" <?php endif;?>>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel__header-title"><?php echo esc_html_e('Add shipping cost', 'ali2woo'); ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-lg-8">
                    <div class="grey-color mb20"><?php echo esc_html_e('You can add the shipping cost that you see in the import list to the product price.', 'ali2woo'); ?></div>
                </div>
            </div>

            <div class="field field_inline">
                <div class="field__label">
                    <label for="a2wl_add_shipping_to_price">
                        <strong><?php echo esc_html_e("Add shipping cost to product price", 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_e("Shipping costs will be added to the product price.", 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox" class="field__input form-control" id="a2wl_add_shipping_to_price" name="a2wl_add_shipping_to_price" value="yes" <?php if (get_setting('add_shipping_to_price')): ?>checked<?php endif;?>/>
                </div>
            </div>

            <div class="field field_inline">
                <div class="field__label">
                    <label for="a2wl_apply_price_rules_after_shipping_cost">
                        <strong><?php echo esc_html_e("Add shipping cost first and then apply the price rules", 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_e("Pricing rules will be applied after shipping cost are added to the product price. This is necessary if you want to add a markup to both the product cost and the shipping cost.", 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox"<?php if (!get_setting('add_shipping_to_price')): ?> disabled="disabled"<?php endif;?> class="field__input form-control" id="a2wl_apply_price_rules_after_shipping_cost" name="a2wl_apply_price_rules_after_shipping_cost" value="yes" <?php if (get_setting('apply_price_rules_after_shipping_cost')): ?>checked<?php endif;?>/>
                </div>
            </div>


        </div>
    </div>

</div>

<div class="container-fluid">
    <div class="row pt20 border-top">
        <div class="col-sm-12">
            <input class="btn btn-success mt20" type="submit" id="save-price-rules" value="<?php echo esc_html_e('Save settings', 'ali2woo'); ?>"/>
        </div>
    </div>
</div>

<div class="modal-overlay modal-apply-pricing-rules">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><?php echo esc_html_e('Apply pricing rules to existing products', 'ali2woo'); ?></h3>
            <a class="modal-btn-close" href="#"></a>
        </div>
        <div class="modal-body">
            <label><?php echo esc_html_e('Select the update type', 'ali2woo'); ?></label>
            <div style="padding-bottom: 20px;">
                <div class="type btn-group" role="group">
                    <button type="button" class="btn btn-default" value="price"><?php echo esc_html_e('Prices', 'ali2woo'); ?></button>
                    <button type="button" class="btn btn-default" value="regular_price"><?php echo esc_html_e('Regular Prices', 'ali2woo'); ?></button>
                    <button type="button" class="btn btn-default" value="all"><?php echo esc_html_e('Prices and Regular Prices', 'ali2woo'); ?></button>
                </div>
            </div>
            <label><?php echo esc_html_e('Select the update scope', 'ali2woo'); ?></label>
            <div>
                <div class="scope btn-group" role="group">
                    <button type="button" class="btn btn-default" value="shop"><?php echo esc_html_e('Shop', 'ali2woo'); ?></button>
                    <button type="button" class="btn btn-default" value="import"><?php echo esc_html_e('Import List', 'ali2woo'); ?></button>
                    <button type="button" class="btn btn-default" value="all"><?php echo esc_html_e('Shop and Import List', 'ali2woo'); ?></button>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <span class="status" style="padding-right: 10px;">xxx</span>
            <button class="btn btn-default close-btn" type="button"><?php echo esc_html_e('Close', 'ali2woo'); ?></button>
            <button class="btn btn-success apply-btn" type="button"><div class="btn-icon-wrap cssload-container"><div class="cssload-speeding-wheel"></div></div><?php echo esc_html_e('Apply', 'ali2woo'); ?></button>
        </div>
    </div>
</div>



<script>
    jQuery(function ($) {
        let pricing_rules_types = <?php echo wp_json_encode($pricingRuleTypes); ?>;
        let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        let nonce_action = '<?php echo wp_create_nonce(AbstractController::AJAX_NONCE_ACTION); ?>';

        $('.js-advanced-prices select.categories').each(function() {
            $(this).select2({
                'width': '90%'
            });
            $(this).on('change', function() {
                checkPriceRules();
            })
        });

        $("#a2wl_pricing_rules_type").on('change', function () {
            $('#pricing_rules_type_description')
                .html(pricing_rules_types[$(this).val()].description);
        });

        $("#a2wl_add_shipping_to_price").on('change', function () {
            $("#a2wl_apply_price_rules_after_shipping_cost")
                .prop('disabled', !$(this).is(':checked'));
        });

        if ($.fn.tooltip) {
            $('[data-toggle="tooltip"]')
                .tooltip({"placement": "left"});
        }

        $(".apply-pricing-rules").on('click', function (event) {
            event.preventDefault();
            $(".modal-apply-pricing-rules .btn-group").each(function () {
                $(this).find('.btn').removeClass('btn-info').removeClass('active').addClass('btn-default');
                $(this).find('.btn:first').removeClass('btn-default').addClass('btn-info').addClass('active');
                $(this).data({value:$(this).find('.btn:first').val()});
            });
            $(".modal-apply-pricing-rules .apply-btn").removeAttr("disabled");
            $(".modal-apply-pricing-rules .status").html("");
            $(".modal-apply-pricing-rules").addClass('opened');
        });

        $(".modal-apply-pricing-rules .close-btn").on('click', function (event) {
            event.preventDefault();
            $(".modal-apply-pricing-rules").removeClass('opened');
        });

        function a2wl_apply_pricing_rules(type, scope, _this_btn) {
            let on_done_calback = function (state, message) {
                if (state!=='ok') {
                    show_notification(message, true);
                } else {
                    show_notification('<?php echo esc_html_e('Applying pricing rules to your existing products', 'ali2woo'); ?>');
                    $(".modal-apply-pricing-rules").removeClass('opened');
                }
                $(_this_btn).removeAttr("disabled");
                setTimeout(function(){
                    location.reload();
                }, 700);
            };

            if (scope=='all' || scope=='import'){
                a2wl_apply_import_pricing_rules(type, 0, function (state, message) {
                    if (state!=='ok') {
                        show_notification(message, true);
                    } else {
                        if (scope=='all') {
                            a2wl_apply_shop_pricing_rules(type, 0, on_done_calback);
                        } else {
                            show_notification('<?php echo esc_html_e('Applying pricing rules to your existing products', 'ali2woo'); ?>');
                            $(".modal-apply-pricing-rules").removeClass('opened');
                            $(_this_btn).removeAttr("disabled");
                        }
                    }

                    setTimeout(function(){
                        location.reload();
                    }, 700);
                });
            } else if (scope=='all' || scope=='shop') {
                a2wl_apply_shop_pricing_rules(type, 0, on_done_calback);
            }
        }

        function a2wl_apply_import_pricing_rules(type, page, on_done_calback){
            let data = {
                'action': 'a2wl_apply_pricing_rules',
                'type':type,'scope':'import',
                'import_page':page,
                'ali2woo_nonce': nonce_action,
            };

            $.post(ajaxurl, data).done(function (response) {
                let json = JSON.parse(response);
                if (json.state==='ok'){
                    $(".modal-apply-pricing-rules .status").html(json.info);
                    if (json.done===0){
                        a2wl_apply_import_pricing_rules(type, page+1, on_done_calback);
                    } else {
                        if(on_done_calback) {
                            on_done_calback(json.state, '');
                        }
                    }
                } else {
                    if (on_done_calback) {
                        on_done_calback(json.state, json.message);
                    }
                }
            }).fail(function (xhr, status, error) {
                if (on_done_calback) {
                    on_done_calback('error', '<?php  esc_html_e('Applying pricing rules failed.', 'ali2woo'); ?>');
                }
            });
        }

        function a2wl_apply_shop_pricing_rules(type, page, on_done_calback)
        {
            let data = {
                'action': 'a2wl_apply_pricing_rules',
                'type':type,
                'scope':'shop',
                'page':page,
                'ali2woo_nonce': nonce_action,
            };

            $.post(ajaxurl, data).done(function (response) {
                let json = JSON.parse(response);
                if (json.state==='ok'){
                    $(".modal-apply-pricing-rules .status").html(json.info);
                    if (json.done===0){
                        a2wl_apply_shop_pricing_rules(type, page+1, on_done_calback);
                    } else {
                        if (on_done_calback){
                            on_done_calback(json.state, '');
                        }
                    }
                } else {
                    if (on_done_calback){
                        on_done_calback(json.state, json.message);
                    }
                }
            }).fail(function (xhr, status, error) {
                if (on_done_calback){
                    on_done_calback('error', '<?php echo esc_html_e('Applying pricing rules failed.', 'ali2woo'); ?>');
                }
            });
        }

        $(".modal-apply-pricing-rules .apply-btn").on("click", function () {
            var _this_btn = $(this);
            $(_this_btn).attr("disabled", true);

            var scope = $(".modal-apply-pricing-rules .btn-group.scope").data().value;
            var type = $(".modal-apply-pricing-rules .btn-group.type").data().value;

            a2wl_apply_pricing_rules(type, scope, _this_btn);

            return false;
        });

        $(".modal-apply-pricing-rules .btn-group .btn").on("click", function () {
            $(this).parents('.btn-group').find('.btn').removeClass('btn-info').removeClass('active').addClass('btn-default');
            $(this).removeClass('btn-default').addClass('btn-info').addClass('active');
            $(this).parents('.btn-group').data({value:$(this).val()});
        });


        $(".global-pricing .dropdown").on("click", function () {
            $(this).next().slideToggle();
        });

        $('.a2wl-content').on("click", ".global-pricing .dropdown-menu li", function (e) {
            e.preventDefault();

            $(this).trigger('change');
            const sign = $(this).attr('data-sign');
            const input = $(this).parents('.price-dropdown-group').find('input[type="text"]');

            $(input).attr('sign', sign)

            $(this).hide().siblings().each(function () {
                $(this).show()
            });
            $(this).parent().fadeOut().prev().html($(this).text());
        });

        //switch-buttons
        $(".price-rulle-toggle").first().on("click", function () {
            $(".js-advanced-prices input[type=checkbox]").trigger("click");
        });

        $(".price-rulle-toggle").eq(1).on("click", function () {
            $(this).trigger('change');
            $(this).toggleClass("price-rulle-toggle--disabled");
            $(".js-advanced-prices").slideToggle();
            $(".js-default-prices").slideToggle();
        });




        $(".js-advanced-prices input[type=checkbox]").on("click", function () {
            comparedPrice();
        })

        function comparedPrice() {
            $(".price-rulle-toggle").first().toggleClass("price-rulle-toggle--disabled");
            $(".price-rulle-toggle").first().parents('.row').find('.switch-col').toggle();
            $(".js-advanced-prices table tr").find("td:eq(-2) .input-group").toggleClass('visually-hidden');
            $(".input-wrap").has("input[id=compared-set-cents]").toggle();
            //$("#cb-compared-set-cents").trigger('click');
        }
        //assign-cents inputs
        $(".assign-cents input:checkbox").on("change", function () {
            var $this = $(this),
                $field = $this.closest('.field');

            if ($this.is(":checked")) {
                $field.find('input:text').prop("disabled", false);
            } else
                $field.find('input:text').prop("disabled", true);
        });

        //our script begin here
        var settings_changed = false;

        $(".global-pricing").on('change', function () {
            if (!settings_changed) {
                settings_changed = true;

                $('a.apply-pricing-rules').hide();
                $('a.apply-pricing-rules').prev().show();

            }
        });


        function get_el_sign_value(el) {
            return el.children('li')
                    .filter(function () {
                        return $(this).css('display') === 'none'
                    })
                    .attr('data-sign');
        }

        function get_value(compared) {
            var s_class = 'compared_value';
            if (typeof compared == "undefined")
                s_class = 'value';

            return $('.js-default-prices .' + s_class).val();
        }

        function rule_info_box_calculation(str_tmpl, sign, value) {

            var def_value = 1, result = value;
            if (sign == "+")
                result = def_value + Number(value);
            if (sign == "*")
                result = def_value * Number(value);

            return sprintf(str_tmpl, def_value, result, def_value, sign, value, result)

        }

        function checkPriceRules() {
            let num_check = true;
            let min_max_check = true;
            let ranges_check = true;
            let rangesCheckFailedCategory = 'global';
            const price_ranges = [];

            $('.js-advanced-prices table tbody .max_price').each(function () {
                let categorySelectNode = $(this).parents('tr').find('select.categories');

                if (isNaN($(this).val()) || isNaN($(this).parents('tr').find('.min_price').val())) {
                    num_check = false;
                } else {
                    if (parseFloat($(this).val()) <= parseFloat($(this).parents('tr').find('.min_price').val())) {
                        min_max_check = false;
                    }
                }

                let selectedCategorySet = [];

                if (isCategorySet(categorySelectNode)) {
                    let selectedOptions = categorySelectNode.find('option:selected');
                    selectedOptions.each(function() {
                        selectedCategorySet.push({
                            id: $(this).val(),
                            title: $(this).text()
                        });
                    });
                } else {
                    selectedCategorySet.push({
                        id: '-1',
                        title: 'Global'
                    });
                }

                for (let index = 0; index < selectedCategorySet.length; ++index) {
                    const categoryId = selectedCategorySet[index].id;
                    const categoryTitle = selectedCategorySet[index].title;

                    if (num_check && !isNaN(parseFloat($(this).val())) && !isNaN(parseFloat($(this).parents('tr').find('.min_price').val()))) {
                        price_ranges.push({
                            'min_price': parseFloat($(this).parents('tr').find('.min_price').val()),
                            'max_price': parseFloat($(this).val()),
                            'category_id': categoryId,
                            'category_title': categoryTitle
                        });
                    }

                }
            });

            //console.log(price_ranges);

            for (let i = 0; i < price_ranges.length; i++) {
                for (let j = 1; j < price_ranges.length; j++) {
                    let priceOverlapCondition = (
                        checkPriceOverlapCondition(price_ranges, i, j) ||
                        checkPriceOverlapCondition(price_ranges, j, i)
                    );

                    let categoryCondition = price_ranges[j].category_id === price_ranges[i].category_id;

                    let finalCondition = i !== j && categoryCondition && priceOverlapCondition;

                    if (finalCondition) {
                        rangesCheckFailedCategory = price_ranges[j].category_title;
                        ranges_check = false;
                        break;
                    }
                }
                if (!ranges_check) {
                    break;
                }
            }

            $('.panel-danger').hide();
            if (!num_check) {
                $('.panel-danger .content').html('<?php echo esc_html_e('Cost value must be a number.', 'ali2woo'); ?>');
                $('.panel-danger').show();
            }
            if (!min_max_check) {
                $('.panel-danger .content').html('<?php echo esc_html_e('Cost range end value must be greater than the starting value.', 'ali2woo'); ?>');
                $('.panel-danger').show();
            }
            if (!ranges_check) {
                $('.panel-danger .content').html('<?php echo esc_html_e(
                        'Your pricing ranges overlap:', 'ali2woo'); ?>' +
                    ' ' + rangesCheckFailedCategory + ' ' +
                    '<?php echo esc_html_e('pricing ranges', 'ali2woo'); ?>'
                );
                $('.panel-danger').show();
            }

            return num_check && min_max_check && ranges_check;
        }

        function checkPriceOverlapCondition(price_ranges, i, j) {
            return  (
                (price_ranges[j].min_price <= price_ranges[i].max_price && price_ranges[i].max_price <= price_ranges[j].max_price) ||
                (price_ranges[j].min_price <= price_ranges[i].min_price && price_ranges[i].min_price <= price_ranges[j].max_price)
            );
        }

        function isCategorySet(selectNode) {
            let value = selectNode.val();
            let isEmpty = Array.isArray(value) && !value.length;

            return !isEmpty;
        }

        function isInt(value) {
            return !isNaN(value) &&
                    parseInt(Number(value)) == value &&
                    !isNaN(parseInt(value, 10));
        }

        function check_cents() {

            function check_cents_range(el) {
                var check = true;

                if (!isInt(el.val()) || el.val() < 0 || el.val() > 99) {
                    el.addClass('has-error');
                    check = false;
                }

                return check;
            }

            var ranges_check1 = true, ranges_check2 = true;

            if ($('#cb-compared-set-cents').is(":checked")) {
                ranges_check1 = check_cents_range($('#compared-set-cents'));
            }

            if ($('#cb-set-cents').is(":checked")) {
                ranges_check2 = check_cents_range($('#set-cents'));
            }

            if (!ranges_check1 || !ranges_check2)
                show_notification('<?php echo esc_html_e('Assign cents field value should be an integer between 1 and 99', 'ali2woo'); ?>', true);

            return ranges_check1 && ranges_check2;
        }

        function set_last_rule_row_enability(show) {
            var row = $('.js-advanced-prices table tbody tr:last-child'),
                    td4 = row.find('td:eq(4)'), td5 = row.find('td:eq(5)');

            if (show) {
                td4.children('.input-group').removeClass('opacity50');
                td5.children('.input-group').removeClass('opacity50');

                td4.find('input[type="text"]').prop('disabled', false);
                td5.find('input[type="text"]').prop('disabled', false);

                td4.find('button').prop('disabled', false);
                td5.find('button').prop('disabled', false);
            } else {
                td4.children('.input-group').removeClass('opacity50').addClass('opacity50');
                td5.children('.input-group').removeClass('opacity50').addClass('opacity50');

                td4.find('input[type="text"]').prop('disabled', true);
                td5.find('input[type="text"]').prop('disabled', true);

                td4.find('button').prop('disabled', true);
                td5.find('button').prop('disabled', true);
            }


        }

        function add_rule_row(this_row) {
            let row = $(this_row).parents('table')
                .find("tbody tr:last-child");
            let newRow = row.clone();

            addCategorySelectToRuleRow(newRow, row.find('select.categories option'));
            newRow.find('.min_price').val(parseFloat($(this_row).find('.max_price').val()) + 0.01);
            newRow.find('.max_price').val('');
            newRow.find('.delete').hide();

            set_last_rule_row_enability(true);
            $('.js-advanced-prices table tbody').append(newRow);

            initSelect2InRuleRow(newRow);

            set_last_rule_row_enability(false);

            row.find('.delete').show();
        }

        function addCategorySelectToRuleRow(newRow, options) {
            let selectNodeContainer = newRow.find('td:first > .field__input-wrap');

            selectNodeContainer.html('');
            let newSelectNode = $('<select class="form-control select2 categories" multiple="multiple">')
                .appendTo(selectNodeContainer);

            $.each(options, function (i, item) {
                newSelectNode.append($('<option>', {
                    value: item.value,
                    text : item.text
                }));
            });
        }

        function initSelect2InRuleRow(newRow) {
            newRow.find('select.categories').select2({
                width: '90%',
                placeholder: 'Any category or choose',
            });
            newRow.find('select.categories').val([]).trigger('change');
        }

        function initSelect2ForSets() {
            $('#a2wl_pricing_rule_set').select2({
                width: '100%',
                placeholder: 'Choose price formula set',
            });
        }

        function initSelect2ForModes() {
            $('#a2wl_pricing_rules_type').select2({
                width: '30%',
                placeholder: 'Choose price formula mode',
            });
        }

        const a2wAjaxApi = (function ($) {
            async function doAjax(params = {}, method = 'POST') {
                return $.ajax({
                    url: ajaxurl,
                    type: method,
                    dataType: 'json',
                    data: params
                });
            }

            return {
                doAjax: doAjax,
            };
        })(jQuery, ajaxurl);

        const a2wSetsAPI = (function ($, ajaxApi) {
            async function save(setName, nonce) {
                let data = {
                    'action': 'a2wl_save_set',
                    'name': setName,
                    'ali2woo_nonce': nonce,
                };

                try {
                    return await ajaxApi.doAjax(data);
                } catch (error) {
                    console.log('save price rule set Error!', error);

                    return {state: 'error', message: error.message};
                }
            }

            async function choose(setName, nonce) {
                let data = {
                    'action': 'a2wl_choose_set',
                    'name': setName,
                    'ali2woo_nonce': nonce,
                };

                try {
                    return await ajaxApi.doAjax(data);
                } catch (error) {
                    console.log('choose price rule set Error!', error);

                    return {state: 'error', message: error.message};
                }
            }

            async function remove(setName, nonce) {
                let data = {
                    'action': 'a2wl_remove_set',
                    'name': setName,
                    'ali2woo_nonce': nonce,
                };

                try {
                    return await ajaxApi.doAjax(data);
                } catch (error) {
                    console.log('remove price rule set Error!', error);

                    return {state: 'error', message: error.message};
                }
            }

            return {
                save: save,
                choose: choose,
                remove: remove,
            }
        })(jQuery, a2wAjaxApi);

        //price formula sets
        initSelect2ForSets();

        $('#a2wl_new_set').on('click', async function() {
            let setName = window.prompt('Enter a unique name for your formula set using only Latin characters and numbers, without spaces.');
            setName = setName ? setName.trim() : null;
            if (!setName) {
                alert('Set name can`t be empty.');
            }

            $('.pricing-rule-set input').prop('disabled', true);
            let result = await a2wSetsAPI.save(setName, nonce_action);

            if (result.state !== 'ok') {
                alert(result.message);
                $('.pricing-rule-set input').prop('disabled', false);
            } else {
                setTimeout(function(){
                    location.reload();
                }, 700);
            }

        });

        $('#a2wl_choose_set').on('click', async function() {
            const selectedSetName = $('#a2wl_pricing_rule_set').val();

            if (selectedSetName) {
                $('.pricing-rule-set input').prop('disabled', true);
                let result = await a2wSetsAPI.choose(selectedSetName, nonce_action);

                if (result.state !== 'ok') {
                    alert(result.message);
                    $('.pricing-rule-set input').prop('disabled', false);
                } else {
                    setTimeout(function(){
                        location.reload();
                    }, 700);
                }
            } else {
                alert('Please select a price formula set from the dropdown.')
            }
        });

        $('#a2wl_remove_set').on('click', async function() {
            const selectedSetName = $('#a2wl_pricing_rule_set').val();

            if (selectedSetName) {
                let confirm = window.confirm(`Do you want to remove price formula set: ${selectedSetName}`);
                if (!confirm) {
                    return false;
                }

                $('.pricing-rule-set input').prop('disabled', true);
                let result = await a2wSetsAPI.remove(selectedSetName, nonce_action);

                if (result.state !== 'ok') {
                    alert(result.message);
                    $('.pricing-rule-set input').prop('disabled', false);
                } else {
                    setTimeout(function(){
                        location.reload();
                    }, 700);
                }
            } else {
                alert('Please select a price formula set from the dropdown.')
            }
        });

        initSelect2ForModes();

        set_last_rule_row_enability(false);

        var price_rule_keyup_timer = false;

        //rule table interactive
        $('.js-advanced-prices table').on('keyup', 'input[type="text"]', function () {
            var this_row = $(this).parents('tr');
            if (price_rule_keyup_timer) {
                clearTimeout(price_rule_keyup_timer);
            }
            price_rule_keyup_timer = setTimeout(function () {
                if (checkPriceRules() && parseFloat($(this_row).parents('table').find("tbody tr:last-child .max_price").val()) > 0) {
                    add_rule_row(this_row);
                }
            }, 1000);
        });

        $('.js-advanced-prices table').on('click', '.delete', function () {
            if ($(this).parents('tr').is(":first-child") && $(this).parents('tbody').find('tr').length < 3) {
                //first action: hide price markup
                $('.price-rulle-toggle').click()
            } else if ($(this).parents('tr').is(":last-child")) {
                //last action must be empty
            } else {
                $(this).trigger('change');
                $(this).parents('tr').remove();
            }
            return false;
        });

        //info content
        //todo: add translation here
        $(".js-default-prices div.info-box").on("mouseover", function () {
            $(this).attr('title', rule_info_box_calculation("E.g., A product that costs %d <?php echo $a2w_local_currency; ?> would have its price set to %d <?php echo $a2w_local_currency; ?> (%d %s %d = %d).", get_el_sign_value($('.js-default-prices ul.sign')), get_value()));
            if(jQuery.fn.tooltip) { $(this).tooltip('fixTitle').tooltip('show'); }
        });

        $(".js-default-prices .compared div.info-box").on("mouseover", function () {
            $(this).attr('title', rule_info_box_calculation("If you import a product that costs %d <?php echo $a2w_local_currency; ?>, we'll set its compared at price to %d <?php echo $a2w_local_currency; ?> (%d %s %d = %d).", get_el_sign_value($('.js-default-prices ul.compared_sign')), get_value(true)));
            if(jQuery.fn.tooltip) { $(this).tooltip('fixTitle').tooltip('show'); }
        });


        //save rules
        $("#save-price-rules").on('click', function () {
            let use_extended_price_markup = !$('.extended_prices .price-rulle-toggle')
                .hasClass('price-rulle-toggle--disabled');

            $('.input-group.has-error, input.has-error').each(function () {
                $(this).removeClass('has-error');
            });

            if (use_extended_price_markup) {

                if (checkPriceRules()) {

                    if (parseFloat($(".js-advanced-prices table tbody tr:last-child .max_price").val()) > 0) {
                        add_rule_row($(".js-advanced-prices table tbody tr:last-child"));
                    }
                    if ($(".js-advanced-prices table tbody tr:last-child .max_price").val().trim() === '' && !isNaN(parseFloat($(".js-advanced-prices table tbody tr:last-child").prev().find('.max_price').val()))) {
                        $(".js-advanced-prices table tbody tr:last-child .min_price").val(parseFloat($(".js-advanced-prices table tbody tr:last-child").prev().find('.max_price').val()) + 0.01)
                    }

                    var price_table = $(".js-advanced-prices table"),
                            use_compared = $('.js-advanced-prices input.use_compared_price_markup').is(":checked"),
                            emptyInputs = $('.js-advanced-prices table tbody tr:not(:last-child) input[type="text"], .js-advanced-prices table tfoot tr input[type="text"]').filter(function () {
                        return $(this).val().trim() === "";
                    });
                    emptyInputs.each(function () {
                        if (use_compared || (!$(this).hasClass('compared_value') && !$(this).hasClass('default_compared_value'))) {
                            $(this).parents('.input-group').addClass('has-error');
                        }
                    });

                    if ($('.js-advanced-prices table .input-group.has-error').length === 0) {
                        if (check_cents()) {
                            let data = {
                                'action': 'a2wl_update_price_rules',
                                'pricing_rules_type':$('#a2wl_pricing_rules_type').val(),
                                'use_extended_price_markup': 'yes',
                                'use_compared_price_markup': use_compared ? 'yes' : 'no', 'rules': [],
                                'default_rule': {'value': $(price_table).find('.default_value').val(),
                                    'sign': get_el_sign_value($(price_table).find('.default_sign')),
                                    'compared_value': $(price_table).find('.default_compared_value').val(),
                                    'compared_sign': get_el_sign_value($(price_table).find('.default_compared_sign'))},
                                'cents': $('#cb-set-cents').is(":checked") ? $('#set-cents').val() : -1,
                                'compared_cents': $('#cb-compared-set-cents').is(":checked") ? $('#compared-set-cents').val() : -1,
                                'add_shipping_to_price': $('#a2wl_add_shipping_to_price').is(":checked")?1:0,
                                'apply_price_rules_after_shipping_cost': $('#a2wl_apply_price_rules_after_shipping_cost').is(":checked")?1:0,
                                'ali2woo_nonce': nonce_action,
                            };

                            $('.js-advanced-prices table tbody tr:not(:last-child)').each(function () {
                                let categorySelectNode = $(this).find('select.categories');

                                let rule = {
                                    'min_price': $(this).find('.min_price').val(),
                                    'max_price': $(this).find('.max_price').val(),
                                    'value': $(this).find('.value').val(),
                                    'sign': get_el_sign_value($(this).find('.sign')),
                                    'compared_value': $(this).find('.compared_value').val(),
                                    'compared_sign': get_el_sign_value($(this).find('.compared_sign')),
                                    'category_ids': categorySelectNode.val(),
                                };
                                data.rules.push(rule);
                            });
                            $.post(ajaxurl, data).done(function (response) {
                                show_notification('Saved successfully.');
                                let json = JSON.parse(response);

                                settings_changed = false;
                                $('a.apply-pricing-rules').show();
                                $('a.apply-pricing-rules').prev().hide();

                            }).fail(function (xhr, status, error) {
                                show_notification('Save failed.', true);
                            });
                        }
                    }
                }

            } else {

                var use_compared = !$('.js-default-prices .price-rulle-toggle').hasClass('price-rulle-toggle--disabled');

                var emptyInputs = $('.js-default-prices input[type="text"]').filter(function () {
                    return $.trim($(this).val()) === "";
                });
                emptyInputs.each(function () {
                    if (use_compared || !$(this).hasClass('compared_value')) {
                        $(this).parents('.input-group').addClass('has-error');
                    }
                });

                if ($('.js-default-prices .input-group.has-error').length === 0) {

                    if (check_cents()) {
                        let data = {
                            'action': 'a2wl_update_price_rules',
                            'pricing_rules_type':$('#a2wl_pricing_rules_type').val(),
                            'use_extended_price_markup': use_extended_price_markup ? 'yes' : 'no',
                            'use_compared_price_markup': use_compared ? 'yes' : 'no',
                            'cents': $('#cb-set-cents').is(":checked") ? $('#set-cents').val() : -1,
                            'compared_cents': $('#cb-compared-set-cents').is(":checked") ? $('#compared-set-cents').val() : -1,
                            'default_rule': {'value': get_value(),
                                'sign': get_el_sign_value($('.js-default-prices ul.sign')),
                                'compared_value': get_value(true),
                                'compared_sign': get_el_sign_value($('.js-default-prices ul.compared_sign'))},
                            'add_shipping_to_price': $('#a2wl_add_shipping_to_price').is(":checked"),
                            'apply_price_rules_after_shipping_cost': $('#a2wl_apply_price_rules_after_shipping_cost').is(":checked"),
                            'ali2woo_nonce': nonce_action,
                        };

                        $.post(ajaxurl, data).done(function (response) {
                            show_notification('Saved successfully.');
                            let json = JSON.parse(response);

                            settings_changed = false;
                            $('a.apply-pricing-rules').show();
                            $('a.apply-pricing-rules').prev().hide();

                        }).fail(function (xhr, status, error) {
                            show_notification('Save failed.', true);
                        });
                    }
                }
            }
            return false;
        });
    });
</script>
