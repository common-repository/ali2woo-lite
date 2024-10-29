<?php

/**
 * Description of ExternalOrderItemAttribute
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class ExternalOrderItemAttribute
{

    function __construct(
        private ?string $name = null,
        private ?string $value = null,
    ) {

    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
