<?php


namespace radondev\ac\utils;


class InformationContainer
{
    /**
     * @var int
     */
    private $value;

    /**
     * InformationContainer constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}