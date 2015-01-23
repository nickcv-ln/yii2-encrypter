<?php
namespace nickcv\encrypter;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    #public $controllerNamespace = 'nickcv\encrypter\commands';
    
    public function init()
    {   
        parent::init();
    }
    
    
    public function bootstrap($app)
    {
        
        if ($app instanceof \yii\console\Application) {
            $this->setAliases([
                'nickcv' =>  dirname(__FILE__),
            ]);
            
            $app->controllerMap[$this->id] = [
                'class' => 'nickcv\encrypter\commands\SetupController',
            ];
        }
    }
}