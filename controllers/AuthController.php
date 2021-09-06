<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller as BaseController;
use app\core\Request;
use app\models\User;

/**
 * Class AuthController
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\controllers
 */
class AuthController extends BaseController
{
    protected User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * AuthController login method.
     *
     * @param Request $request
     * @return string|false
     */
    public function login(Request $request): string|false
    {
        # Check if method is post.
        if ($request->isPost()) {
            # Process login method.
        }

        # Set page layout before rendering.
        # In "/../views/layouts" folder.
        $this->setLayout(layout: 'auth');

        # Render the login page.
        return $this->render(view: 'login');
    }

    /**
     * AuthController register method.
     *
     * @param Request $request
     * @return string|false
     */
    public function register(Request $request): string|false
    {
        # Check if method is post.
        if ($request->isPost()) {
            # Get all input fields.
            $this->user->loadData(data: $request->getBody());

            # Validate and save to database.
            if ($this->user->validate() && $this->user->save()) {
                # Add flash message with 'success' key.
                Application::$app->session->setFlash(
                    key: 'success',
                    message: 'Congratulations, your account has been successfully created.'
                );

                # Redirect if success.
                Application::$app->response->redirect(location: '/');
            }
        }

        # Set page layout.
        $this->setLayout(layout: 'auth');

        # Render register page with model $user.
        return $this->render(
            view: 'register',
            params: ['model' => $this->user]
        );
    }
}
