# Installation

`composer require walkboy/yii2-basic-access:dev-master`


# Using

In your controller:

```php
public function behaviors()
{
    return [
    	
    	//...

        'basicAccess' => [
            'class' => \walkboy\BasicAccess\BasicAccess::class,
            'logins' => ['user:pass'],
            //'realm' => 'My Project',
            //'msg_unauthorized' => 'Please enter a valid user name and password to access.',
        ],
        
        //...

    ];
}
```
