<?php

namespace app\core;

/**
 * Class Controller
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
class Controller
{
    public string $layout = 'main';

    /**
     * Controller set page layout.
     *
     * @param string $layout
     */
    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    /**
     * Controller render page.
     *
     * @param string $view
     * @param array $params
     * @return string|false
     */
    public function render(string $view, array $params = []): string|false
    {
        return Application::$app
            ->router
            ->renderView(
                view: $view,
                params: $params
            );
    }
}
