<?php

/**
 * Description of FulfillmentClientInterface
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

interface FulfillmentClientInterface
{
    /**
     * @param OrderPreviewDataDto $OrderPreviewData
     * @return array
     */
    public function getOrderPreview(OrderPreviewDataDto $OrderPreviewData): array;
}
