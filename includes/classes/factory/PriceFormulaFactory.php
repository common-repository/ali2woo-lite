<?php
/**
 * Description of PriceFormulaFactory
 *
 * @author Ali2Woo Team
 *
 */

namespace AliNext_Lite;;

class PriceFormulaFactory
{
    public function createFormulaFromData(array $data): PriceFormula
    {
        $data['id'] = !empty($data['id']) ? intval($data['id']) : 0;
        $data['category'] = !empty($data['category']) ? intval($data['category']) : null;

        if (!empty($data['category_ids'])) {
            $categoryIds = [];
            foreach ($data['category_ids'] as $categoryId) {
                $categoryIds[] = intval($categoryId);
            }
            $data['category_ids'] = $categoryIds;
        } else {
            $data['category_ids'] = null;
        }

        $data['category_name'] = !empty($data['category_name']) ? $data['category_name'] : null;
        $data['min_price'] = isset($data['min_price']) ? floatval($data['min_price']) : null;
        $data['max_price'] = isset($data['max_price']) ? floatval($data['max_price']) : null;
        $data['sign'] = $data['sign'] ?? '*';
        $data['value'] = isset($data['value']) ? floatval($data['value']) : null;
        $data['compared_sign'] = $data['compared_sign'] ?? '*';
        $data['compared_value'] = isset($data['compared_value']) ? floatval($data['compared_value']) : null;

        return (new PriceFormula())
            ->setId($data['id'])
            ->setCategory($data['category'])
            ->setCategoryIds($data['category_ids'])
            ->setCategoryName($data['category_name'])
            ->setMinPrice($data['min_price'])
            ->setMaxPrice($data['max_price'])
            ->setSign($data['sign'])
            ->setValue($data['value'])
            ->setComparedSign($data['compared_sign'])
            ->setComparedValue($data['compared_value']);
    }

    public function createFormula(): PriceFormula
    {
        return new PriceFormula();
    }

}
