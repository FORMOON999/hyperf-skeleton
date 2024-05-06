<?php

namespace App\Common\Util\PhpGenerator\Printer;

use App\Common\Util\PhpGenerator\Constant;
use App\Common\Util\PhpGenerator\GenerateClass;
use App\Common\Util\PhpGenerator\Method;
use App\Common\Util\PhpGenerator\Property;

interface PrinterInterface
{
    /**
     * class
     *
     * @param GenerateClass $generateClass
     *
     * @return mixed
     */
    public function printClass(GenerateClass $generateClass): string;

    /**
     * constant
     *
     * @param Constant $constant
     *
     * @return mixed
     */
    public function printConstant(Constant $constant): string;

    /**
     * property
     *
     * @param Property $property
     *
     * @return mixed
     */
    public function printProperty(Property $property): string;

    /**
     * method
     *
     * @param Method $method
     *
     * @return mixed
     */
    public function printMethod(Method $method): string;
}
