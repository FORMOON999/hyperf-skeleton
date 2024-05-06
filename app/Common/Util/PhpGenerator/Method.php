<?php

namespace App\Common\Util\PhpGenerator;

use App\Common\Util\PhpGenerator\Printer\PrinterFactory;

class Method extends Base
{
    /**
     * @var bool
     */
    private $final = false;

    /**
     * @var bool
     */
    private $abstract = false;

    /**
     * @var Params[]
     */
    private $params = [];

    /**
     * @var mixed
     */
    private $return;

    /**
     * @var ?string
     */
    private $content;

    /**
     * @return bool
     */
    public function getFinal(): bool
    {
        return $this->final;
    }

    /**
     * @param bool $final
     *
     * @return Method
     */
    public function setFinal(bool $final = true): Method
    {
        $this->final = $final;
        return $this;
    }

    /**
     * @return Params[]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param Params[] $params
     *
     * @return Method
     */
    public function setParams(array $params): Method
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param mixed $return
     *
     * @return Method
     */
    public function setReturn($return): Method
    {
        $this->return = $return;
        return $this;
    }

    /**
     * @param Params $params
     *
     * @return $this
     */
    public function addParams(Params $params): Method
    {
        $this->params[] = $params;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param ?string $content
     *
     * @return Method
     */
    public function setContent(?string $content): Method
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAbstract(): bool
    {
        return $this->abstract;
    }

    /**
     * @param bool $abstract
     *
     * @return Method
     */
    public function setAbstract(bool $abstract = true): Method
    {
        $this->abstract = $abstract;
        return $this;
    }

    public function __toString(): string
    {
        return PrinterFactory::getInstance()->getPrinter($this->getVersion())->printMethod($this);
    }
}
