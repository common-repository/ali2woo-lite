<?php

/**
 * Description of ImportJobInterface
 *
 * @author Ali2Woo Team
 *
 * @position: 2
 */

namespace AliNext_Lite;;

interface ImportJobInterface extends BaseJobInterface
{
    public function pushToQueue(int $product_id, int $external_id, array $steps, bool $start = true): self;
}
