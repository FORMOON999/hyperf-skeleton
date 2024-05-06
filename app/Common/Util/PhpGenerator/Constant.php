<?php

namespace App\Common\Util\PhpGenerator;

use App\Common\Util\PhpGenerator\Printer\PrinterFactory;

class Constant extends Base
{
    /**
     * @var mixed
     */
    private $default;

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
     * @return Constant
     */
    public function setDefault($default): Constant
    {
        $this->default = $default;
        return $this;
    }

    public function __toString(): string
    {
        return PrinterFactory::getInstance()->getPrinter($this->getVersion())->printConstant($this);
    }
}
