<?php

/**
 * Description of PermanentAlert
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class ProductsFromFileResult
{
    public function __construct(
        private int $idsCount, private array $processErrorsIds
    ) {}

    public function getIdsCount(): int
    {
        return $this->idsCount;
    }

    public function getProcessErrorsIds(): array
    {
        return $this->processErrorsIds;
    }
}
