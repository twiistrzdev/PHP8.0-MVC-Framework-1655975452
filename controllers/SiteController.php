<?php

namespace app\controllers;

use app\core\Controller as BaseController;
use app\core\Request;

/**
 * Class SiteController
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\controllers
 */
class SiteController extends BaseController
{
    /**
     * SiteController home method.
     *
     * @return string|false
     */
    public function home(): string|false
    {
        return $this->render(
            view: 'home',
            params: ['name' => 'Daishitie']
        );
    }

    /**
     * SiteController about method.
     *
     * @return string|false
     */
    public function about(): string|false
    {
        return $this->render(view: 'about');
    }

    /**
     * SiteController contact method
     *
     * @param Request $request
     * @return string|false
     */
    public function contact(Request $request): string|false
    {
        if ($request->isPost()) {
            return 'Handling submitted data';
        }

        return $this->render(view: 'contact');
    }
}
