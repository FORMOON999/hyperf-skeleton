<?php

namespace App\Common\Util\PhpGenerator;

use App\Common\Util\PhpGenerator\Printer\PrinterFactory;

class Property extends Base
{
    /**
     * @var mixed
     */
    private $default;

    /**
     * @var bool
     */
    private $annotation;

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param $default
     *
     * @return Property
     */
    public function setDefault($default): Property
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAnnotation(): bool
    {
        return $this->annotation;
    }

    /**
     * @param bool $annotation
     */
    public function setAnnotation(bool $annotation): void
    {
        $this->annotation = $annotation;
    }

    public function __toString(): string
    {
        return PrinterFactory::getInstance()->getPrinter($this->getVersion())->printProperty($this);
    }
}
