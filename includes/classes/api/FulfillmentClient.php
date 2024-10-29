<?php

/**
 * Description of FulfillmentClient
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class FulfillmentClient extends AbstractClient implements FulfillmentClientInterface
{
    /**
     * @param OrderPreviewDataDto $OrderPreviewData
     * @return array
     * @throws ApiException
     */
    public function getOrderPreview(OrderPreviewDataDto $OrderPreviewData): array
    {
        $params = [
            'country_code' => $OrderPreviewData->getCountryCode(),
            'country' => $OrderPreviewData->getCountry(),
            'province' => $OrderPreviewData->getProvince(),
            'city' => $OrderPreviewData->getCity(),
            'items' => [],
        ];

        foreach ($OrderPreviewData->getItems() as $item) {
            $params['items'][] = [
                'quantity' => $item->getQuantity(),
                'product_id' => $item->getProductId(),
                'sku_id' => $item->getSkuId(),
                'image_url' => $item->getImageUrl(),
                'attributes' => $item->getAttributes(),
            ];
        }

        $json = wp_json_encode($params);

        $args = [
            'headers' => ['Content-Type' => 'application/json'],
        ];

        $request_url = RequestHelper::build_request('get_order_preview');
        $request = a2wl_remote_post($request_url, $json, $args);
        $result = $this->handleRequestResult($request);

        if ($result['state'] == 'error') {
            throw new ApiException('Fulfillment Client Error', $result['error_code'] ?? 0);
        }

        return $result;
    }
}
