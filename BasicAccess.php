<?php

namespace walkboy\BasicAccess;

use yii\base\ActionFilter;

class BasicAccess extends ActionFilter
{
	public $logins = null;
	public $realm = 'WWW';
	public $msg_unauthorized = 'Please enter a valid user name and password to access.';

    public function beforeAction($action)
    {
    	if ($this->logins) {

	    	for (; 1; $this->authenticate()) {

	    	    if (!isset($_SERVER['PHP_AUTH_USER'])) {
	    	        continue;
	    	    }

	    	    $user = $_SERVER['PHP_AUTH_USER'];
	    	    $pwd = $_SERVER['PHP_AUTH_PW'];

	    	    $tmp = preg_grep("/$user:.*$/", $this->logins);
	    	    if (!($authUserLine = array_shift($tmp))) {
	    	        continue;
	    	    }

	    	    preg_match("/$user:((..).*)$/", $authUserLine, $matches);
	    	    if (empty($matches)) {
	    	        continue;
                }
	    	    $pwd_stored = $matches[1];
	    	    
	    	    if ($pwd != $pwd_stored) {
	    	        continue;
	    	    }

	    	    break;
	    	}
	    }
        
		//throw new UnauthorizedHttpException('Unauthorized.');
		   
        return parent::beforeAction($action);
    }

    public function authenticate()
    {
	    header('WWW-Authenticate: Basic realm="'.$this->realm.'"');
	    header('HTTP/1.0 401 Unauthorized');
	    echo $this->msg_unauthorized;
	    exit;
	}

}
