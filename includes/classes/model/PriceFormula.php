<?php

/**
 * Description of PriceFormula
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class PriceFormula
{
    public const TYPE_ALL = 'all';
    public const TYPE_PRICE = 'price';
    public const TYPE_REGULAR_PRICE = 'regular_price';


    public const FIELD_ID = 'id';
    public const FIELD_CATEGORY = 'category';
    public const FIELD_CATEGORY_IDS = 'category_ids';
    public const FIELD_CATEGORY_NAME = 'category_name';
    public const FIELD_MIN_PRICE = 'min_price';
    public const FIELD_MAX_PRICE = 'max_price';
    public const FIELD_SIGN = 'sign';
    public const FIELD_VALUE = 'value';
    public const FIELD_COMPARED_SIGN = 'compared_sign';
    public const FIELD_COMPARED_VALUE = 'compared_value';


    public int $id = 0;
    public ?int $category = null;
    public ?array $categoryIds = null;
    public ?string $category_name = null;
    public ?float $min_price = null;
    public ?float $max_price = null;
    public string $sign = '*';
    public ?float $value = null;
    public string $compared_sign = '*';
    public ?float $compared_value = null;

    public static function pricing_rules_types()
    {
        $helpUrl = esc_url( 'https://help.ali2woo.com/codex/pricing-markup-formula/');
        $helpUrlText = esc_html__('this article', 'ali2woo');
        $salePriceText = esc_html__('sale price', 'ali2woo');
        $regularPriceText = esc_html__('regular price', 'ali2woo');

        $salePriceAndDiscountText = sprintf(
            esc_html__(
                'AliNext (Lite version) gets your %s by applying a pricing formula to the AliExpress sale price. Your %s is calculated using the %s part of the formula.',
                'ali2woo'),
            sprintf(
                '<strong>%s</strong> ',
                $salePriceText
            ),
            sprintf(
                '<strong>%s</strong>',
                $regularPriceText
            ),
            sprintf(
                '<i>%s</i>',
                $regularPriceText
            )
        );

        $salePriceAndDiscountText .= '<br/>' . sprintf(
            esc_html__('If you disable that part, the %s is formed from your calculated sale price increased by the original AliExpress discount.', 'ali2woo'),
            sprintf(
                '<strong>%s</strong> ',
                $regularPriceText
            )
        );

        $salePriceAndDiscountText .= '<br/>' . sprintf(
            esc_html__('To learn more check out %s', 'ali2woo'),
            /* translators: %1$s is replaced with a link to help resource, %1$s is replaced with a link text */
            sprintf(
                '<a href="%1$s">%2$s</a>',
                $helpUrl,
                $helpUrlText
            ),
        );

        $salePriceText = sprintf(
            esc_html__('Your pricing formula is applied to the AliExpress sale price. To learn more check out %s', 'ali2woo'),
            /* translators: %1$s is replaced with a link to help resource, %1$s is replaced with a link text */
            sprintf(
                '<a href="%1$s">%2$s</a>',
                $helpUrl,
                $helpUrlText
            ),
        );

        $regularPriceText = sprintf(
            esc_html__('Your pricing formula is applied to the AliExpress regular price. To learn more check out %s', 'ali2woo'),
            /* translators: %1$s is replaced with a link to help resource, %1$s is replaced with a link text */
            sprintf(
                '<a href="%1$s">%2$s</a>',
                $helpUrl,
                $helpUrlText
            ),
        );

        return [
            PriceFormulaService::SALE_PRICE_AND_DISCOUNT => [
                'value' => PriceFormulaService::SALE_PRICE_AND_DISCOUNT,
                'name' => esc_html__('Use sale price and discount', 'ali2woo'),
                'description' => $salePriceAndDiscountText,
            ],
            PriceFormulaService::SALE_PRICE_AS_BASE => [
                'value' => PriceFormulaService::SALE_PRICE_AS_BASE,
                'name' => esc_html__('Use sale price as base', 'ali2woo'),
                'description' => $salePriceText,
            ],
            PriceFormulaService::REGULAR_PRICE_AS_BASE => [
                'value' => PriceFormulaService::REGULAR_PRICE_AS_BASE ,
                'name' => esc_html__('Use regular price as base', 'ali2woo'),
                'description' => $regularPriceText,
            ],
        ];
    }

    public function __construct(?array $data = null)
    {
        //todo: remove this and use setter and getter instead
        if ($data) {
            foreach ($data as $field => $value) {
                if (property_exists(get_class($this), $field)) {
                    $this->$field = esc_attr($value);
                }
            }
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(?int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(?string $categoryName): self
    {
        $this->category_name = $categoryName;

        return $this;
    }

    public function getMinPrice(): ?float
    {
        return $this->min_price;
    }

    public function setMinPrice(?float $minPrice): self
    {
        $this->min_price = $minPrice;

        return $this;
    }

    public function getMaxPrice(): ?float
    {
        return $this->max_price;
    }

    public function setMaxPrice(?float $maxPrice): self
    {
        $this->max_price = $maxPrice;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getComparedValue(): ?float
    {
        return $this->compared_value;
    }

    public function setComparedValue(?float $comparedValue): self
    {
        $this->compared_value = $comparedValue;

        return $this;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function setSign(string $sign): self
    {
        $this->sign = $sign;

        return $this;
    }

    public function getComparedSign(): string
    {
        return $this->compared_sign;
    }

    public function setComparedSign(string $comparedSign): self
    {
        $this->compared_sign = $comparedSign;

        return $this;
    }

    public function getCategoryIds(): ?array
    {
        return $this->categoryIds;
    }

    public function setCategoryIds(?array $categoryIds): self
    {
        $this->categoryIds = $categoryIds;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_ID => $this->getId(),
            self::FIELD_CATEGORY => $this->getCategory(),
            self::FIELD_CATEGORY_IDS => $this->getCategoryIds(),
            self::FIELD_CATEGORY_NAME => $this->getCategoryName(),
            self::FIELD_MIN_PRICE => $this->getMinPrice(),
            self::FIELD_MAX_PRICE => $this->getMaxPrice(),
            self::FIELD_SIGN => $this->getSign(),
            self::FIELD_VALUE => $this->getValue(),
            self::FIELD_COMPARED_SIGN => $this->getComparedSign(),
            self::FIELD_COMPARED_VALUE => $this->getComparedValue(),
        ];
    }

}
