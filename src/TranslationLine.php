<?php

namespace Translate;

class TranslationLine
{
    const LINE_TYPE_SECTION = 0;
    const LINE_TYPE_TRANSLATION = 1;

    /** @var string */
    private $identifier;

    /** @var string */
    private $value;

    /** @var int */
    private $type;

    public function __construct(string $identifier, string $value, int $type)
    {
        $this->identifier = $identifier;
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}