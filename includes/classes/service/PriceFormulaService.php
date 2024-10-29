<?php

/**
 * Description of PermanentAlert
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class PriceFormulaService
{
    protected PriceFormulaRepository $PriceFormulaRepository;
    protected PriceFormulaSettingsRepository $PriceFormulaSettingsRepository;

    public const REGULAR_PRICE_AS_BASE = 'regular_price_as_base';
    public const SALE_PRICE_AS_BASE = 'sale_price_as_base';
    public const SALE_PRICE_AND_DISCOUNT = 'sale_price_and_discount';

    public function __construct(
        PriceFormulaRepository $PriceFormulaRepository, PriceFormulaSettingsRepository $PriceFormulaSettingsRepository
    ) {
        $this->PriceFormulaRepository = $PriceFormulaRepository;
        $this->PriceFormulaSettingsRepository = $PriceFormulaSettingsRepository;
    }

    public function applyFormula(array $product, int $round = 2, string $applyFormulaTo = PriceFormula::TYPE_ALL): array
    {
        $pricing_rules_type = $this->PriceFormulaSettingsRepository->getPricingRulesType();
        //todo: add this method to the test
        $apply_price_rules_after_shipping_cost = $this->PriceFormulaSettingsRepository
            ->getApplyPriceRulesAfterShippingCost();

        $shipping_cost = 0;
        //todo: add this method to the test
        if ($this->PriceFormulaSettingsRepository->getAddShippingToPrice() && !empty($product['shipping_cost'])) {
            $shipping_cost = round($product['shipping_cost'], $round);
        }

        if ($this->PriceFormulaSettingsRepository->getUseSeparateFormula()) {
            $formula = $this->getFormulaByProduct($product, PriceFormula::TYPE_PRICE);
            $formula_regular = $this->getFormulaByProduct($product, PriceFormula::TYPE_REGULAR_PRICE);
        } else {
            $price_type = $pricing_rules_type == self::REGULAR_PRICE_AS_BASE ?
                PriceFormula::TYPE_REGULAR_PRICE :
                PriceFormula::TYPE_PRICE;

            $formula = $this->getFormulaByProduct($product, $price_type);
            $formula_regular = $this->getFormulaByProduct($product, $price_type);
        }

        $product_price = $this->normalizeProductPrice($product);

        if ($formula && $formula_regular && $product_price['price']) {
            $use_compared_price_markup = $this->PriceFormulaSettingsRepository->getUseComparedPriceMarkup();
            $price_cents = $this->PriceFormulaSettingsRepository->getPriceCents();
            $price_compared_cents = $this->PriceFormulaSettingsRepository->getPriceComparedCents();

            if ($applyFormulaTo === PriceFormula::TYPE_ALL || $applyFormulaTo === PriceFormula::TYPE_PRICE || !isset($product['calc_price'])) {
                // calculate price
                $price = $pricing_rules_type == self::REGULAR_PRICE_AS_BASE ?
                    $product_price['regular_price'] :
                    $product_price['price'];

                if ($formula->sign == "=") {
                    $product['calc_price'] = round(floatval($formula->value) + $shipping_cost, $round);
                } else if ($formula->sign == "*") {
                    if ($apply_price_rules_after_shipping_cost) {
                        $product['calc_price'] = round((floatval($price) + $shipping_cost) * floatval($formula->value), $round);
                    } else {
                        $product['calc_price'] = round(floatval($price) * floatval($formula->value) + $shipping_cost, $round);
                    }
                } else if ($formula->sign == "+") {
                    $product['calc_price'] = round(floatval($price) + $shipping_cost + floatval($formula->value), $round);
                }

                if (!empty($product['calc_price']) && $price_cents > -1) {
                    $product['calc_price'] = round(floor($product['calc_price']) + ($price_cents / 100), 2);
                }
            }

            if ($applyFormulaTo === PriceFormula::TYPE_ALL || $applyFormulaTo === PriceFormula::TYPE_REGULAR_PRICE || !isset($product['calc_regular_price'])) {
                // calculate regular_price
                if ($pricing_rules_type == PriceFormulaService::SALE_PRICE_AND_DISCOUNT) {
                    // use source discount
                    if (isset($product['discount']) && isset($product['calc_price'])) {
                        $product['calc_regular_price'] = round($product['calc_price'] * 100 / (100 - min(99.9, floatval($product['discount']))), $round);
                    }

                    if ($use_compared_price_markup) {
                        if ($formula_regular->compared_sign == "=") {
                            $product['calc_regular_price'] = round(floatval($formula_regular->compared_value) + $shipping_cost, $round);
                        } else if ($formula_regular->compared_sign == "*") {
                            if ($apply_price_rules_after_shipping_cost) {
                                $product['calc_regular_price'] = round((floatval($product_price['price']) + $shipping_cost) * floatval($formula_regular->compared_value), $round);
                            } else {
                                $product['calc_regular_price'] = round(floatval($product_price['price']) * floatval($formula_regular->compared_value) + $shipping_cost, $round);
                            }
                        } else if ($formula_regular->compared_sign == "+") {
                            $product['calc_regular_price'] = round(floatval($product_price['price']) + $shipping_cost + floatval($formula_regular->compared_value), $round);
                        }
                    }

                } else {
                    $price = $pricing_rules_type == self::REGULAR_PRICE_AS_BASE ?
                        $product_price['regular_price'] :
                        $product_price['price'];

                    if ($use_compared_price_markup) {
                        if ($formula_regular->compared_sign == "=") {
                            $product['calc_regular_price'] = round(floatval($formula_regular->compared_value) + $shipping_cost, $round);
                        } else if ($formula_regular->compared_sign == "*") {
                            if ($apply_price_rules_after_shipping_cost) {
                                $product['calc_regular_price'] = round((floatval($price) + $shipping_cost) * floatval($formula_regular->compared_value), $round);
                            } else {
                                $product['calc_regular_price'] = round(floatval($price) * floatval($formula_regular->compared_value) + $shipping_cost, $round);
                            }
                        } else if ($formula_regular->compared_sign == "+") {
                            $product['calc_regular_price'] = round(floatval($price) + $shipping_cost + floatval($formula_regular->compared_value), $round);
                        }
                    } else {
                        if ($formula_regular->sign == "=") {
                            $product['calc_regular_price'] = round(floatval($formula_regular->value) + $shipping_cost, $round);
                        } else if ($formula_regular->sign == "*") {
                            if ($apply_price_rules_after_shipping_cost) {
                                $product['calc_regular_price'] = round((floatval($price) + $shipping_cost) * floatval($formula_regular->value), $round);
                            } else {
                                $product['calc_regular_price'] = round(floatval($price) * floatval($formula_regular->value) + $shipping_cost, $round);
                            }
                        } else if ($formula_regular->sign == "+") {
                            $product['calc_regular_price'] = round(floatval($price) + $shipping_cost + floatval($formula_regular->value), $round);
                        }
                    }
                }

                if (!empty($product['calc_regular_price']) && $price_compared_cents > -1) {
                    $product['calc_regular_price'] = round(floor($product['calc_regular_price']) + ($price_compared_cents / 100), 2);
                }

                if (!empty($product['calc_regular_price']) && !empty($product['calc_price']) && $product['calc_regular_price'] < $product['calc_price']) {
                    $product['calc_regular_price'] = $product['calc_price'];
                }
            }

            if (isset($product['sku_products']['variations']) && $product['sku_products']['variations']) {
                foreach ($product['sku_products']['variations'] as &$var) {
                    if ($this->PriceFormulaSettingsRepository->getUseSeparateFormula()) {
                        $formula = $this->getFormulaByProduct($var, PriceFormula::TYPE_PRICE);
                        $formula_regular = $this->getFormulaByProduct($var, PriceFormula::TYPE_REGULAR_PRICE);
                    } else {
                        $price_type = $pricing_rules_type == self::REGULAR_PRICE_AS_BASE ?
                            PriceFormula::TYPE_REGULAR_PRICE :
                            PriceFormula::TYPE_PRICE;

                        $formula = $this->getFormulaByProduct($var, $price_type);
                        $formula_regular = $this->getFormulaByProduct($var, $price_type);
                    }

                    if ($formula && $formula_regular) {
                        if ($applyFormulaTo === PriceFormula::TYPE_ALL || $applyFormulaTo === PriceFormula::TYPE_PRICE || !isset($var['calc_price'])) {
                            // calculate price
                            $price = $pricing_rules_type == self::REGULAR_PRICE_AS_BASE ?
                                $var['regular_price'] :
                                $var['price'];

                            if ($formula->sign == "=") {
                                $var['calc_price'] = round(floatval($formula->value) + $shipping_cost, $round);
                            } else if ($formula->sign == "*") {
                                if ($apply_price_rules_after_shipping_cost) {
                                    $var['calc_price'] = round((floatval($price) + $shipping_cost) * floatval($formula->value), $round);
                                } else {
                                    $var['calc_price'] = round(floatval($price) * floatval($formula->value) + $shipping_cost, $round);
                                }
                            } else if ($formula->sign == "+") {
                                $var['calc_price'] = round(floatval($price) + $shipping_cost + floatval($formula->value), $round);
                            }

                            if (!empty($var['calc_price']) && $price_cents > -1) {
                                $var['calc_price'] = round(floor($var['calc_price']) + ($price_cents / 100), 2);
                            }
                        }

                        if ($applyFormulaTo === PriceFormula::TYPE_ALL || $applyFormulaTo === PriceFormula::TYPE_REGULAR_PRICE || !isset($var['calc_regular_price'])) {
                            // calculate regular_price
                            if ($pricing_rules_type == PriceFormulaService::SALE_PRICE_AND_DISCOUNT) {
                                // use source discount
                                if (isset($var['discount']) && isset($var['calc_price'])) {
                                    $var['calc_regular_price'] = round($var['calc_price'] * 100 / (100 - min(99.9, floatval($var['discount']))), $round);
                                }
                                if ($use_compared_price_markup) {
                                    if ($formula_regular->compared_sign == "=") {
                                        $var['calc_regular_price'] = round(floatval($formula_regular->compared_value) + $shipping_cost, $round);
                                    } else if ($formula_regular->compared_sign == "*") {
                                        if ($apply_price_rules_after_shipping_cost) {
                                            $var['calc_regular_price'] = round((floatval($var['price']) + $shipping_cost) * floatval($formula_regular->compared_value), $round);
                                        } else {
                                            $var['calc_regular_price'] = round(floatval($var['price']) * floatval($formula_regular->compared_value) + $shipping_cost, $round);
                                        }
                                    } else if ($formula_regular->compared_sign == "+") {
                                        $var['calc_regular_price'] = round(floatval($var['price']) + $shipping_cost + floatval($formula_regular->compared_value), $round);
                                    }
                                }

                            } else {
                                $price = $pricing_rules_type == self::REGULAR_PRICE_AS_BASE ?
                                    $var['regular_price'] :
                                    $var['price'];

                                if ($use_compared_price_markup) {
                                    if ($formula_regular->compared_sign == "=") {
                                        $var['calc_regular_price'] = round(floatval($formula_regular->compared_value) + $shipping_cost, $round);
                                    } else if ($formula_regular->compared_sign == "*") {
                                        if ($apply_price_rules_after_shipping_cost) {
                                            $var['calc_regular_price'] = round((floatval($price) + $shipping_cost) * floatval($formula_regular->compared_value), $round);
                                        } else {
                                            $var['calc_regular_price'] = round(floatval($price) * floatval($formula_regular->compared_value) + $shipping_cost, $round);
                                        }
                                    } else if ($formula_regular->compared_sign == "+") {
                                        $var['calc_regular_price'] = round(floatval($price) + $shipping_cost + floatval($formula_regular->compared_value), $round);
                                    }
                                } else {
                                    if ($formula_regular->sign == "=") {
                                        $var['calc_regular_price'] = round(floatval($formula_regular->value) + $shipping_cost, $round);
                                    } else if ($formula_regular->sign == "*") {
                                        if ($apply_price_rules_after_shipping_cost) {
                                            $var['calc_regular_price'] = round((floatval($price) + $shipping_cost) * floatval($formula_regular->value), $round);
                                        } else {
                                            $var['calc_regular_price'] = round(floatval($price) * floatval($formula_regular->value) + $shipping_cost, $round);
                                        }
                                    } else if ($formula_regular->sign == "+") {
                                        $var['calc_regular_price'] = round(floatval($price) + $shipping_cost + floatval($formula_regular->value), $round);
                                    }
                                }
                            }

                            $regular_price_cents = $use_compared_price_markup ? $price_compared_cents : $price_cents;
                            if (!empty($var['calc_regular_price']) && $regular_price_cents > -1) {
                                $var['calc_regular_price'] = round(floor($var['calc_regular_price']) + ($regular_price_cents / 100), 2);
                            }

                            if (!empty($var['calc_regular_price']) && !empty($var['calc_price']) && $var['calc_regular_price'] < $var['calc_price']) {
                                $var['calc_regular_price'] = $var['calc_price'];
                            }
                        }
                    }
                }
            }
        }

        return $product;
    }

    public function getFormulaByProduct(array $product, string $base_price_type = PriceFormula::TYPE_PRICE): PriceFormula
    {
        $res_formula = false;
        $use_extended_price_markup = $this->PriceFormulaSettingsRepository->getUseExtendedPriceMarkup();

        if ($use_extended_price_markup) {
            $base_price_type = in_array($base_price_type, [PriceFormula::TYPE_PRICE, PriceFormula::TYPE_REGULAR_PRICE]) ?
                $base_price_type : PriceFormula::TYPE_PRICE;

            $product_price = $this->normalizeProductPrice($product);
            $product_price = $product_price[$base_price_type];
            if ($product_price) {
                $GetExtendedFormulasGroupedResult = $this->PriceFormulaRepository->getExtendedFormulasGrouped();

                $CategoryPriceFormula = $this->getCategoryFormulaByProduct(
                    $product, $product_price, $GetExtendedFormulasGroupedResult->getCategoryRules()
                );

                if (!is_null($CategoryPriceFormula)) {
                    return $CategoryPriceFormula;
                }

                $formula_list = $GetExtendedFormulasGroupedResult->getGlobalRules();

                foreach ($formula_list as $PriceFormula) {
                    $check = true;

                    if (!is_null($PriceFormula->getMinPrice()) && $PriceFormula->getMinPrice() > $product_price) {
                        $check = false;
                    }

                    if (!is_null($PriceFormula->getMaxPrice()) && $PriceFormula->getMaxPrice() < $product_price) {
                        $check = false;
                    }

                    if (!is_null($PriceFormula->getCategory()) &&
                        $PriceFormula->getCategory() != intval($product['category_id'])
                    ) {
                        $check = false;
                    }

                    if ($check) {
                        $res_formula = $PriceFormula;
                        break;
                    }
                }
            } else {
                a2wl_error_log("can't find normalize product price for " . $product['id']);
            }
        }

        return $res_formula ?: $this->PriceFormulaRepository->getDefaultFormula();
    }

    public function getCategoryFormulaByProduct(
        array $product, float $testedProductPrice, array $PriceFormulasWithCategories
    ): ?PriceFormula {

        if (empty($PriceFormulasWithCategories)) {
            return null;
        }

        if (empty($product['categories'])) {
            return null;
        }

        $res_formula = false;

        $formula_list = $PriceFormulasWithCategories;
        foreach ($formula_list as $PriceFormula) {
            $check = true;

            if (!is_null($PriceFormula->getMinPrice()) && $PriceFormula->getMinPrice() > $testedProductPrice) {
                $check = false;
            }

            if (!is_null($PriceFormula->getMaxPrice()) && $PriceFormula->getMaxPrice() < $testedProductPrice) {
                $check = false;
            }

            if (!is_null($PriceFormula->getCategoryIds())) {
                $intersection = array_intersect(
                    $PriceFormula->getCategoryIds(),
                    $product['categories']
                );

                if (empty($intersection)) {
                    $check = false;
                }
            }

            if ($check) {
                $res_formula = $PriceFormula;
                break;
            }
        }

        return $res_formula ?: null;
    }

    public function normalizeProductPrice(array $product): array
    {
        $price = $regular_price = 0;
        if (isset($product['price']) && floatval($product['price'])) {
            $price = $regular_price = floatval($product['price']);
            if (isset($product['regular_price']) && floatval($product['regular_price'])) {
                $regular_price = floatval($product['regular_price']);
            }
        } else if (isset($product['price_min']) && floatval($product['price_min'])) {
            $price = $regular_price = floatval($product['price_min']);
            if (isset($product['regular_price_min']) && floatval($product['regular_price_min'])) {
                $regular_price = floatval($product['regular_price_min']);
            }
        } else if (isset($product['price_max']) && floatval($product['price_max'])) {
            $price = $regular_price = floatval($product['price_max']);
            if (isset($product['regular_price_max']) && floatval($product['regular_price_max'])) {
                $regular_price = floatval($product['regular_price_max']);
            }
        }

        return [
            'price' => $price,
            'regular_price' => $regular_price,
        ];
    }

}
