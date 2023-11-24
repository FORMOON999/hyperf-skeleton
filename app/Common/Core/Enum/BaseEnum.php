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

namespace App\Common\Core\Enum;

use App\Common\Core\Enum\Annotation\EnumMessage;
use Hyperf\Contract\TranslatorInterface;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;
use MabeEnum\Enum;
use MabeEnum\EnumSerializableTrait;
use ReflectionClassConstant;
use Serializable;

abstract class BaseEnum extends Enum implements Serializable
{
    use EnumSerializableTrait;

    protected ?TranslatorInterface $translator = null;

    /**
     * 获得.
     */
    public function getMessage(array $replace = [], ?string $locale = null): string
    {
        $message = $this->_getMessage($replace);
        $translator = $this->getTranslator();
        if (is_null($translator)) {
            return $message;
        }
        return $translator->trans($message, $replace, $locale);
    }

    //    public static function getMessages(array $replace = []): array
    //    {
    //        $classname = get_called_class();
    //        $reflect = new ReflectionClass($classname);
    //        $constants = $reflect->getReflectionConstants();
    //        $data = [];
    //        foreach ($constants as $constant) {
    //            $data[] = self::handleMessage($constant, $replace);
    //        }
    //        return $data;
    //    }

    /**
     * map.
     * @return array
     */
    public static function getMapJson()
    {
        $data = [];
        $values = static::getValues();
        foreach ($values as $value) {
            $data[] = [
                'value' => $value,
                'message' => static::byValue($value)->getMessage(),
            ];
        }
        return $data;
    }

    protected function getTranslator(): ?TranslatorInterface
    {
        if (! empty($this->translator)) {
            return $this->translator;
        }
        $this->translator = \Hyperf\Support\make(TranslatorInterface::class);
        return $this->translator;
    }

    /**
     * @return array
     */
    protected function parse(string $doc, array $previous = [])
    {
        $pattern = '/\\@(\\w+)\\(\\"(.+)\\"\\)/U';
        if (preg_match_all($pattern, $doc, $result)) {
            if (isset($result[1], $result[2])) {
                $keys = $result[1];
                $values = $result[2];

                foreach ($keys as $i => $key) {
                    if (isset($values[$i])) {
                        $previous[strtolower($key)] = $values[$i];
                    }
                }
            }
        }
        return $previous;
    }

    protected function handleMessage($constant, array $replace = []): string
    {
        $message = '';
        if (version_compare(PHP_VERSION, '8.0.0', '>')) {
            $attributes = $constant->getAttributes(EnumMessage::class);
            if (! empty($attributes)) {
                $message = $attributes[0]->newInstance()->message;
            }
        }

        if (empty($message)) {
            $constantDocComment = $constant->getDocComment();
            $message = ArrayHelper::getValue($this->parse($constantDocComment), 'message', '');
        }

        $arr = [];
        foreach ($replace as $key => $value) {
            if (! str_contains($key, ':')) {
                $key = ":{$key}";
            }
            $arr[$key] = $value;
        }
        return strtr($message, $arr);
    }

    /**
     * 获得.
     */
    private function _getMessage(array $replace = []): string
    {
        $classname = get_called_class();
        $constant = new ReflectionClassConstant($classname, $this->getName());
        return $this->handleMessage($constant, $replace);
    }
}
