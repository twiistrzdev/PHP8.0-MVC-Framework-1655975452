<?php

namespace app\core;

/**
 * Class Router
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
class Router
{
    protected array $routes = [];

    public Request $request;
    public Response $response;

    /**
     * Router constructor.
     *
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Router get method.
     *
     * @param string $path
     * @param string|array|object $callback
     */
    public function get(string $path, string|array|object $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * Router post method.
     *
     * @param string $path
     * @param string|array|object $callback
     */
    public function post(string $path, string|array|object $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * Router resolve.
     *
     * @return mixed
     */
    public function resolve(): mixed
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(code: 404);
            return $this->renderView(view: "_404");
        }

        if (is_string($callback)) {
            return $this->renderView(view: $callback);
        }

        if (is_array($callback)) {
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }

        return call_user_func($callback, $this->request);
    }

    /**
     * Router render view.
     *
     * @param string $view
     * @param array $params
     * @return string|array
     */
    public function renderView(string $view, array $params = []): string|array
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView(view: $view, params: $params);

        return str_replace(
            search: "{{content}}",
            replace: $viewContent,
            subject: $layoutContent
        );
    }

    /**
     * Router render content.
     *
     * @param string|array $viewContent
     * @return string|array
     */
    public function renderContent(string|array $viewContent): string|array
    {
        $layoutContent = $this->layoutContent();

        return str_replace(
            search: '{{content}}',
            replace: $viewContent,
            subject: $layoutContent
        );
    }

    /**
     * Router protected layout content.
     *
     * @return string|false
     */
    protected function layoutContent(): string|false
    {
        $layout = Application::$app->controller->layout ?? "main";

        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    /**
     * Router protected render only view.
     *
     * @param string $view
     * @param array $params
     * @return string|false
     */
    protected function renderOnlyView(string $view, array $params): string|false
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
