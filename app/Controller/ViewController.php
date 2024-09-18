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

namespace App\Controller;

use App\Common\Core\BaseController;
use Hyperf\HttpServer\Annotation\AutoController;

use function Hyperf\ViewEngine\view;

#[AutoController(prefix: 'view')]
class ViewController extends BaseController
{
    public function child()
    {
        return $this->view->render('child', ['name' => 'ssss']);
    }
}
