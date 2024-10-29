<?php

/**
 * Description of BaseJobInterface
 *
 * @author Ali2Woo Team
 *
 * @position: 1
 */

namespace AliNext_Lite;;

interface BaseJobInterface
{
    public function getSize(): int;
    public function getName(): string;
    public function getTitle(): string;
    public function cancel(): void;
    public function isQueued(): bool;
    public function isCancelled(): bool;

}
