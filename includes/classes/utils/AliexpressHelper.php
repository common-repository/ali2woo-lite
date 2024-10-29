<?php

/**
 * Description of AliexpressHelper
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class AliexpressHelper
{
    public function convertToAliexpressCountryCode(string $countryCode): string
    {
        return ProductShippingMeta::normalize_country($countryCode);
    }
}
