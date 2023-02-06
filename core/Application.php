<?php

namespace app\core;

use app\models\userModel;
use app\models\userTokenModel;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;

    public string $userClass;
    public string $userType = '';
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $database;
    public Session $session;
    public Cookie $cookie;
    public ?userModel $user;
    private array $rootInfo;

    public function __construct(public string $rootPath, array $config)

    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        $this->userClass = $config['userClass'];
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->database = new Database($config['db']);
        $this->rootInfo = $config['root'];

        $this->settingLoggedData();

        if($this->cookie->isRememberMeSet()) {
            if($this->rememberLogin()) {
                $this->response->redirect('/');
            }
        }
    }

    public static function session() : Session
    {
        return self::$app->session;
    }

    public static function cookie() : Cookie
    {
        return self::$app->cookie;
    }

    public function run() : void
    {
        try {
            ob_start();
            echo $this->router->resolve();
            ob_end_flush();
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->response->setStatusCode($e->getCode());
            $this->router->render('_error', $e->getMessage(),[
                'exception' => $e
            ]);
        }
    }

    public function login($user): bool
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        if(session_regenerate_id()) {
            $this->session->set('user', $primaryValue);
            $this->session->set('username', $user->username);
            $this->session->set('userType', $user->userType());
            return true;
        }
        return false;
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
        return $this->userClass::getUserClass($userType);

    }

    public function isRoot(string $username): bool
    {
        return $username === $this->rootInfo['username'];
    }

    public function isRootPassword(string $password): bool
    {
        return password_verify($password, $this->rootInfo['password']);
    }

    private function getSelectorNValidator(): array
    {
        $selectorNValidator = $this->cookie->getCookie('rememberMe');
        return explode(':', $selectorNValidator);
    }

    private function settingLoggedData(): void {
        $primaryValue = $this->session->get('user');
        if($primaryValue) {
            $primaryKey = $this->userClass::getPrimaryKey();
            $this->user = $this->userClass::getModel([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
            if(!$this->session->get('userType')) {
                $this->session->set('userType', 'guest');
            }
        }
    }

    private function rememberLogin(): bool {
        [$selector, $validator] = $this->getSelectorNValidator();
        $userToken = userTokenModel::getModel(['selector' => $selector]);
        if(!$userToken) {
            $this->cookie->unsetCookie('rememberMe');
            return false;
        }
        if(date('Y-m-d H:i:s') > $userToken->expiryDate) {
            $this->cookie->unsetCookie('rememberMe');
            $userToken->delete(['selector' => $selector]);
            return false;
        }
        if(password_verify($validator, $userToken->validator)) {
            echo 'remember login';
            return $this->login(userModel::getModel(['userID' => $userToken->userID]));
        }
        return false;
    }
}
