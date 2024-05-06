<?php

namespace App\Common\Util\PhpGenerator;


use App\Common\Core\BaseObject;

class Params extends BaseObject
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $type;

    /**
     * @var string
     */
    private $comment = '';

    /**
     * @var mixed
     */
    private $default;

    /**
     * 是否 赋值
     * @var bool
     */
    private $assign = false;

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return Params
     */
    public function setDefault($default): Params
    {
        $this->setAssign(true);
        $this->default = $default;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Params
     */
    public function setName(string $name): Params
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return Params
     */
    public function setType($type): Params
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return Params
     */
    public function setComment(string $comment): Params
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAssign(): bool
    {
        return $this->assign;
    }

    /**
     * @param bool $assign
     *
     * @return Params
     */
    protected function setAssign(bool $assign): Params
    {
        $this->assign = $assign;
        return $this;
    }
}
