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

namespace App\Common\Core;

use App\Common\Core\Annotation\ArrayType;
use App\Common\Core\Annotation\EnumView;
use App\Common\Helpers\FormatHelper;
use App\Common\Helpers\StringHelper;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use RuntimeException;

class BaseObject implements ArrayableInterface
{
    // 严格模式 赋值
    private bool $_strict = false;

    // 字段 下划线
    private bool $_underlineName = false;

    // 字段 驼峰
    private bool $_humpName = false;

    public function __construct(array|ArrayableInterface $config = [])
    {
        $config = is_object($config) ? $config->toArray() : $config;
        if (! empty($config)) {
            $this->configure($this, $config);
        }
        $this->init();
    }

    public function __setObject(string $classname, $value): object
    {
        return (object) $value;
    }

    /**
     * getter.
     *
     * @param mixed $name
     * @return mixed
     * @throws RuntimeException
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        $camelize = FormatHelper::camelize($name);
        if (property_exists($this, $camelize)) {
            return $this->{$camelize};
        }

        return $this->{$name};
    }

    /**
     * setter.
     *
     * @param mixed $name
     * @param mixed $value
     * @throws RuntimeException
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
            return;
        }

        $camelize = FormatHelper::camelize($name);
        if (property_exists($this, $camelize)) {
            $this->{$camelize} = $value;
            return;
        }

        $this->{$name} = $value;
    }

    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function init() {}

    public function getStrict(): bool
    {
        return $this->_strict;
    }

    public function setStrict(bool $strict = true): BaseObject
    {
        $this->_strict = $strict;
        return $this;
    }

    public function configure($object, array $properties)
    {
        $class = new ReflectionObject($object);
        $factory = DocBlockFactory::createInstance();
        $context = new Context($class->getNamespaceName(), Reflection::getUseStatements($class));
        $isPhp8 = version_compare(PHP_VERSION, '8.0.0', '>');
        foreach ($properties as $name => $value) {
            $camelize = FormatHelper::camelize($name);
            $setter = 'set' . ucfirst($camelize);
            switch (true) {
                case $class->hasMethod($setter):
                    $value = $this->getDocBlock($class->getMethod($setter), $factory, $context, $value, 'param', $isPhp8);
                    $object->{$setter}($value);
                    break;
                case $class->hasProperty($name):
                    $object->{$name} = $this->getDocBlock($class->getProperty($name), $factory, $context, $value, 'var', $isPhp8);
                    break;
                case $class->hasProperty($camelize):
                    $object->{$camelize} = $this->getDocBlock($class->getProperty($camelize), $factory, $context, $value, 'var', $isPhp8);
                    break;
                default:
                    if (! $this->getStrict()) {
                        $object->{$name} = $value;
                    }
                    break;
            }
        }
    }

    public function getUnderlineName(): bool
    {
        return $this->_underlineName;
    }

    public function setUnderlineName(bool $underlineName = true): BaseObject
    {
        $this->_underlineName = $underlineName;
        return $this;
    }

    public function getHumpName(): bool
    {
        return $this->_humpName;
    }

    public function setHumpName(bool $humpName = true): BaseObject
    {
        $this->_humpName = $humpName;
        return $this;
    }

    public function toArray(): array
    {
        $class = new ReflectionObject($this);
        $isPhp8 = version_compare(PHP_VERSION, '8.0.0', '>');
        $data = $this->getObjectData($class, $this, $isPhp8);
        while ($class->getParentClass()) {
            $class = new ReflectionClass($class->getParentClass()->getName());
            if (! $class->isInstantiable()) {
                continue;
            }
            $parent = $this->getObjectData($class, $this, $isPhp8);
            $data = array_merge($data, $parent);
        }
        return $data;
    }

    private function createObject(string $classname, $value): object
    {
        if (is_object($value)) {
            return $value;
        }

        if (enum_exists($classname)) {
            return $classname::from($value);
        }

        $class = new ReflectionClass($classname);
        if ($class->isInterface()) {
            return $this->__setObject($classname, $value);
        }

        if (! is_subclass_of($classname, BaseObject::class)) {
            return $class->newInstance($value);
        }

        $model = $class
            ->newInstance()
            ->setStrict($this->getStrict())
            ->setUnderlineName($this->getUnderlineName())
            ->setHumpName($this->getHumpName());
        if (! empty($value)) {
            $model->configure($model, $value);
        }
        return $model;
    }

    private function fromDocBlock(TagWithType $tagWithType, $value)
    {
        $type = $tagWithType->getType();
        switch (true) {
            case $type instanceof Compound:
                foreach ($type->getIterator() as $item) {
                    if ($item instanceof Object_) {
                        $value = $this->createObject($item->getFqsen()->__toString(), $value);
                        break;
                    }
                }
                break;
            case $type instanceof Nullable && method_exists($type->getActualType(), 'getFqsen'):
                $value = $this->createObject($type->getActualType()->getFqsen()->__toString(), $value);
                break;
            case $type instanceof Object_:
                $value = $this->createObject($type->getFqsen()->__toString(), $value);
                break;
            case $type instanceof Array_ && $type->getValueType() instanceof Object_:
                foreach ($value as $key => $item) {
                    $value[$key] = $this->createObject($type->getValueType()->getFqsen()->__toString(), $item);
                }
                break;
        }
        return $value;
    }

    private function getDocBlockByProperty($class, $value, $isPhp8): array
    {
        $isHandle = false;
        if ($class instanceof ReflectionProperty) {
            $type = $class->getType();
            if (! $type) {
                return [$value, $isHandle];
            }
            if (! $type->isBuiltin()) {
                $isHandle = true;
                $value = $this->createObject($type->getName(), $value);
            }
            if ($type->getName() === 'array' && $isPhp8) {
                $arrayTypes = $class->getAttributes(ArrayType::class);
                if (! empty($arrayTypes)) {
                    $isHandle = true;
                    $arrayType = $arrayTypes[0]->newInstance();
                    if ($arrayType->className) {
                        foreach ($value as $key => $item) {
                            $value[$key] = $this->createObject($arrayType->className, $item);
                        }
                    }
                }
            }
        }
        return [$value, $isHandle];
    }

    private function getDocBlock($class, $factory, $context, $value, $tagName, $isPhp8)
    {
        [$value, $isHandle] = $this->getDocBlockByProperty($class, $value, $isPhp8);
        if ($isHandle) {
            return $value;
        }

        $docComment = $class->getDocComment();
        if (empty($docComment)) {
            return $value;
        }
        $block = $factory->create($docComment, $context);
        $tags = $block->getTagsByName($tagName);
        if (empty($tags)) {
            return $value;
        }
        $tag = current($tags);
        return $this->fromDocBlock($tag, $value);
    }

    private function fromValue($property, $value, $isPhp8)
    {
        switch (true) {
            case is_array($value):
                foreach ($value as $key => $item) {
                    $value[$key] = $this->fromValue($property, $item, $isPhp8);
                }
                break;
            case is_object($value):
                if (enum_exists($value::class)) {
                    $flags = EnumView::ENUM_VALUE;
                    if ($isPhp8 && $enumViews = $property->getAttributes(EnumView::class)) {
                        $flags = $enumViews[0]->newInstance()->flags;
                    }
                    switch ($flags) {
                        case EnumView::ENUM_NAME:
                            $value = $value->name;
                            break;
                        case EnumView::ENUM_VALUE:
                            $value = $value->value;
                            break;
                        case EnumView::ENUM_MESSAGE:
                            $value = $value->getMessage();
                            break;
                        case EnumView::ENUM_ALL:
                            $value = [
                                'value' => $value->value,
                                'message' => $value->getMessage(),
                            ];
                            break;
                    }
                } elseif (method_exists($value, 'toArray')) {
                    if ($value instanceof BaseObject) {
                        $value = $value
                            ->setStrict($this->getStrict())
                            ->setUnderlineName($this->getUnderlineName())
                            ->setHumpName($this->getHumpName());
                    }
                    $value = $value->toArray();
                }
                break;
        }
        return $value;
    }

    private function getObjectData(ReflectionClass $class, $object, $isPhp8)
    {
        $data = [];
        $properties = $class->getProperties();

        foreach ($properties as $property) {
            if ($property->isPrivate()) {
                continue;
            }
            if ($property->isProtected()) {
                $property->setAccessible(true);
            }
            if (! $property->isInitialized($object)) {
                continue;
            }
            $name = $property->getName();
            $value = $object->{$name};
            if (is_null($value)) {
                continue;
            }
            if (! StringHelper::startsWith($name, '_')) {
                switch (true) {
                    case $this->getUnderlineName():
                        $name = FormatHelper::uncamelize($name);
                        break;
                    case $this->getHumpName():
                        $name = FormatHelper::camelize($name);
                        break;
                }
            }
            $data[$name] = $this->fromValue($property, $value, $isPhp8);
        }
        return $data;
    }
}
