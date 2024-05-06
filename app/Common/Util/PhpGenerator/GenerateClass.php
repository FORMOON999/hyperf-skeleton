<?php

namespace App\Common\Util\PhpGenerator;

use App\Common\Core\BaseObject;
use App\Common\Util\PhpGenerator\Printer\PrinterFactory;

class GenerateClass extends BaseObject
{
    /**
     * @var int
     */
    private $version = PrinterFactory::VERSION_PHP72;

    /**
     * @var bool
     */
    private $strictTypes = false;

    /**
     * @var bool
     */
    private $final = false;

    /**
     * @var bool
     */
    private $abstract = false;

    /**
     * @var bool
     */
    private $interface = false;

    /**
     * @var ?string
     */
    private $namespace;

    /**
     * @var array
     */
    private $uses = [];

    /**
     * @var string
     */
    private $classname;

    /**
     * @var ?string
     */
    private $inheritance;

    /**
     * @var array
     */
    private $comments = [];

    /**
     * @var array
     */
    private $implements = [];

    /**
     * @var Constant[]
     */
    private $constants = [];

    /**
     * @var Method[]
     */
    private $methods = [];

    /**
     * @var Property[]
     */
    private $properties = [];

    /**
     * @var array
     */
    private $traits = [];

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return GenerateClass
     */
    public function setVersion(int $version): GenerateClass
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStrictTypes(): bool
    {
        return $this->strictTypes;
    }

    /**
     * @param bool $strictTypes
     *
     * @return GenerateClass
     */
    public function setStrictTypes(bool $strictTypes = true): GenerateClass
    {
        $this->strictTypes = $strictTypes;
        return $this;
    }

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
     * @return GenerateClass
     */
    public function setFinal(bool $final): GenerateClass
    {
        $this->final = $final;
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
     * @return GenerateClass
     */
    public function setAbstract(bool $abstract): GenerateClass
    {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * @return bool
     */
    public function getInterface(): bool
    {
        return $this->interface;
    }

    /**
     * @param bool $interface
     *
     * @return GenerateClass
     */
    public function setInterface(bool $interface): GenerateClass
    {
        $this->interface = $interface;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string|null $namespace
     *
     * @return GenerateClass
     */
    public function setNamespace(?string $namespace): GenerateClass
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return array
     */
    public function getUses(): array
    {
        return $this->uses;
    }

    /**
     * @param array $uses
     *
     * @return GenerateClass
     */
    public function setUses(array $uses): GenerateClass
    {
        $this->uses = $uses;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassname(): string
    {
        return $this->classname;
    }

    /**
     * @param string $classname
     *
     * @return GenerateClass
     */
    public function setClassname(string $classname): GenerateClass
    {
        $this->classname = $classname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInheritance(): ?string
    {
        return $this->inheritance;
    }

    /**
     * @param string|null $inheritance
     *
     * @return GenerateClass
     */
    public function setInheritance(?string $inheritance): GenerateClass
    {
        $this->inheritance = $inheritance;
        return $this;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param array $comments
     *
     * @return GenerateClass
     */
    public function setComments(array $comments): GenerateClass
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return array
     */
    public function getImplements(): array
    {
        return $this->implements;
    }

    /**
     * @param array $implements
     *
     * @return GenerateClass
     */
    public function setImplements(array $implements): GenerateClass
    {
        $this->implements = $implements;
        return $this;
    }

    /**
     * @return Constant[]
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @param Constant[] $constants
     *
     * @return GenerateClass
     */
    public function setConstants(array $constants): GenerateClass
    {
        $this->constants = $constants;
        return $this;
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param Method[] $methods
     *
     * @return GenerateClass
     */
    public function setMethods(array $methods): GenerateClass
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param Property[] $properties
     *
     * @return GenerateClass
     */
    public function setProperties(array $properties): GenerateClass
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param string $use
     *
     * @return GenerateClass
     */
    public function addUse(string $use): GenerateClass
    {
        if (!in_array($use, $this->uses)) {
            $this->uses[] = $use;
        }
        return $this;
    }

    /**
     * @param string $comment
     *
     * @return GenerateClass
     */
    public function addComment(string $comment): GenerateClass
    {
        $this->comments[] = $comment;
        return $this;
    }

    /**
     * @param string $implement
     *
     * @return GenerateClass
     */
    public function addImplement(string $implement): GenerateClass
    {
        $this->implements[] = $implement;
        return $this;
    }

    /**
     * @param Constant $constant
     *
     * @return GenerateClass
     */
    public function addCont(Constant $constant): GenerateClass
    {
        $this->constants[] = $constant;
        return $this;
    }

    /**
     * @param Property $property
     *
     * @return GenerateClass
     */
    public function addProperty(Property $property): GenerateClass
    {
        $this->properties[] = $property;
        return $this;
    }

    /**
     * @param Method $method
     *
     * @return GenerateClass
     */
    public function addMethod(Method $method): GenerateClass
    {
        $this->methods[] = $method;
        return $this;
    }

    /**
     * @return array
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @param array $traits
     * @return GenerateClass
     */
    public function setTraits(array $traits): GenerateClass
    {
        $this->traits = $traits;
        return $this;
    }

    /**
     * @param string $trait
     * @return $this
     */
    public function addTrait(string $trait): GenerateClass
    {
        $this->traits[] = $trait;
        return $this;
    }

    public function __toString(): string
    {
        return PrinterFactory::getInstance()->getPrinter($this->getVersion())->printClass($this);
    }

}
