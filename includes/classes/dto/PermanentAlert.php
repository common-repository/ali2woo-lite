<?php

/**
 * Description of PermanentAlert
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class PermanentAlert
{
    public const TYPE_INFO = 'info';
    public const TYPE_SUCCESS = 'success';
    public const TYPE_ERROR = 'danger';
    public function __construct(private string $content, private string $type = self::TYPE_INFO) {}

    public function getContent(): string
    {
        return $this->content;
    }

    public function getType(): string
    {
        return $this->type;
    }

}
