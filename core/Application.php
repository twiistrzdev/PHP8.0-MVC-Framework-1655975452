<?php

namespace app\core;

/**
 * Class Application
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
class Application
{
    public static string $ROOT_DIR;
    public static Application $app;

    public Database $db;
    public Router $router;
    public Request $request;
    public Session $session;
    public Response $response;
    public Controller $controller;

    /**
     * App Constructor.
     */
    public function __construct(
        private string $rootPath,
        private array $config,
    ) {
        self::$ROOT_DIR = $this->rootPath;
        self::$app = $this;

        $this->db = new Database(config: $this->config['db']);

        $this->request = new Request();
        $this->session = new Session();
        $this->response = new Response();
        $this->controller = new Controller();

        $this->router = new Router(
            request: $this->request,
            response: $this->response
        );
    }

    /**
     * Application controller setter.
     *
     * @param Controller $controller
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Application controller getter.
     *
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * Run Application
     */
    public function run()
    {
        echo $this->router->resolve();
    }
}
