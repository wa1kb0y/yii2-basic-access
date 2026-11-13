<?php

namespace walkboy\BasicAccess;

use Yii;
use yii\base\ActionFilter;

class BasicAccess extends ActionFilter
{
	public $logins = null;
	public $realm = 'WWW';
	public $msg_unauthorized = 'Please enter a valid user name and password to access.';

    public function beforeAction($action)
    {
        if ($this->logins) {
            [$user, $pwd] = Yii::$app->getRequest()->getAuthCredentials();
            if (!static::validate((array)$this->logins, $user, $pwd)) {
                return $this->authenticate();
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * Checks credentials against "user:password" lines: exact user name match, constant-time comparison,
     * lines with an empty password never match.
     */
    public static function validate(array $logins, ?string $user, ?string $pwd): bool
    {
        if ($user === null || $user === '' || $pwd === null) {
            return false;
        }
        foreach ($logins as $line) {
            $parts = explode(':', (string)$line, 2);
            if (count($parts) === 2 && $parts[1] !== ''
                && hash_equals($parts[0], $user) && hash_equals($parts[1], $pwd)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Responds with 401 and a Basic auth challenge, the action is not run
     */
    public function authenticate(): bool
    {
        $response = Yii::$app->getResponse();
        $response->setStatusCode(401);
        $response->getHeaders()->set('WWW-Authenticate', 'Basic realm="' . addcslashes($this->realm, '"\\') . '"');
        $response->content = $this->msg_unauthorized;

        return false;
    }

}
