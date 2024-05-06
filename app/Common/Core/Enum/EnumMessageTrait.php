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

trait EnumMessageTrait
{
    /**
     * 获得.
     */
    public function getMessage(array $replace = [], ?string $locale = null): string
    {
        $message = $this->getDocCommentMessage($replace);
        $translator = \Hyperf\Support\make(TranslatorInterface::class);
        if (is_null($translator)) {
            return $message;
        }
        return $translator->trans($message, $replace, $locale);
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
}
