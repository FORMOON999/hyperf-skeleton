<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Common\Util\PhpGenerator;

use App\Common\Core\BaseObject;
use App\Common\Util\PhpGenerator\Printer\PrinterFactory;

/**
 * Class Base.
 */
class Base extends BaseObject
{
    /**
     * @var int
     */
    protected $version = PrinterFactory::VERSION_PHP72;

    /**
     * @var bool
     */
    protected $static = false;

    /**
     * @var bool
     */
    protected $private = false;

    /**
     * @var bool
     */
    protected $protected = false;

    /**
     * @var bool
     */
    protected $public = true;

    /**
     * @var array
     */
    protected $comments = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $type;

    public function __valueType($value): string
    {
        switch (gettype($value)) {
            case 'boolean':
                $value = 'bool';
                break;
            case 'integer':
                $value = 'int';
                break;
            case 'double':
                $value = 'float';
                break;
            case 'NULL':
                $value = 'null';
                break;
            default:
                break;
        }
        return $value;
    }

    public function __getValue($value): string
    {
        if (is_null($this->getType())) {
            $this->setType(gettype($value));
        }
        switch ($this->getType()) {
            case 'boolean':
                $value = $value ? 'true' : 'false';
                break;
            case 'integer':
            case 'double':
                $value = (string) $value;
                break;
            case 'string':
                $value = "'" . $value . "'";
                break;
            case 'resource':
                $value = '{resource}';
                break;
            case 'NULL':
                $value = 'null';
                break;
            case 'unknown type':
                $value = '{unknown}';
                break;
            case 'array':
                $value = json_encode($value);
                break;
        }
        return $value;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): Base
    {
        $this->version = $version;
        return $this;
    }

    public function getStatic(): bool
    {
        return $this->static;
    }

    public function setStatic(bool $static = true): Base
    {
        $this->static = $static;
        return $this;
    }

    public function getPrivate(): bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private = true): Base
    {
        $this->private = $private;
        if ($private === true) {
            $this->setPublic(false);
            $this->setProtected(false);
        }
        return $this;
    }

    public function getProtected(): bool
    {
        return $this->protected;
    }

    public function setProtected(bool $protected = true): Base
    {
        $this->protected = $protected;
        if ($protected === true) {
            $this->setPublic(false);
            $this->setPrivate(false);
        }
        return $this;
    }

    public function getPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public = true): Base
    {
        $this->public = $public;
        if ($public === true) {
            $this->setProtected(false);
            $this->setPrivate(false);
        }
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Base
    {
        $this->name = $name;
        return $this;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function setComments(array $comments): Base
    {
        $this->comments = $comments;
        return $this;
    }

    public function addComment(string $comment): Base
    {
        $this->comments[] = $comment;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
