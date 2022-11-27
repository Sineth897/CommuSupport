<?php

namespace app\core;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;

    public string $userClass;
    public string $userType = 'guest';
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $database;
    public Session $session;
    public ?UserModel $user;

    public function __construct(public string $rootPath, array $config)

    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        $this->userClass = $config['userClass'];
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        $this->database = new Database($config['db']);

        $primaryValue = $this->session->get('user');
        if($primaryValue) {
            $primaryKey = $this->userClass::getPrimaryKey();
            $this->user = $this->userClass::getUser([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
            if(!$this->session->get('userType')) {
                $this->session->set('userType', 'guest');
            }
        }
    }

    public static function session()
    {
        return self::$app->session;
    }

    public function run() : void
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            $this->router->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function login($user): bool
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $userType = $user->userType();
        $this->session->set('user', $primaryValue);
        $this->session->set('userType', $userType);
        return true;
    }

    public function logout(): void
    {
        $this->user = null;
        $this->session->remove('user');
        $this->session->set('userType','guest');
    }

    public function userType()
    {
        return $this->session->get('userType');
    }

    private function getUserClass(string $userType): string
    {
        $userClass = $this->userClass;
        $userClass = $userClass::getUserClass($userType);
        return $userClass;
    }
}
