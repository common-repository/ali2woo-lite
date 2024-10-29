<?php
namespace AliNext_Lite;;

use Exception;
use Throwable;

class FactoryException extends Exception
{
    protected ?array $extraData = null;

    public function __construct($message, ?array $extraData = null, int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        if ($this->extraData) {
            return __CLASS__ . ": [{$this->code}]: {$this->message} \n [Extra data]: "
                . print_r($this->extraData, true) . "\n";
        } else {
            return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }
    }

    public function getExtraData(): ?array
    {
        return $this->extraData;
    }

}
