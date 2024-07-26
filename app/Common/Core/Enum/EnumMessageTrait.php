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
use App\Common\Helpers\Arrays\ArrayHelper;
use Hyperf\Contract\TranslatorInterface;
use ReflectionEnumBackedCase;

trait EnumMessageTrait
{
    public function getMessage(array $replace = [], ?string $locale = null): string
    {
        $message = $this->handleMessage($replace);
        $translator = \Hyperf\Support\make(TranslatorInterface::class);
        if (is_null($translator)) {
            return $message;
        }
        return $translator->trans($message, $replace, $locale);
    }

    protected function handleMessage(array $replace = []): string
    {
        $message = '';

        $reflection = new ReflectionEnumBackedCase($this, $this->name);
        $attributes = $reflection->getAttributes(EnumMessage::class);
        if (! empty($attributes)) {
            $message = $attributes[0]->newInstance()->message;
        }

        if (empty($message)) {
            $constantDocComment = $reflection->getDocComment();
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

    protected function parse(string $doc, array $previous = []): array
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
}
