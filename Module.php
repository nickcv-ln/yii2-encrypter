<?php
/**
 * Contains the module class used to have encrypt console commands.
 * 
 * @link http://www.creationgears.com/
 * @copyright Copyright (c) 2015 Nicola Puddu
 * @license http://www.gnu.org/copyleft/gpl.html
 * @package nickcv/yii2-encrypter
 * @author Nicola Puddu <n.puddu@outlook.com>
 */
namespace nickcv\encrypter;

use yii\base\BootstrapInterface;

/**
 * Bootstrap the module to allow the use of the console commands.
 * 
 * @author Nicola Puddu <n.puddu@outlook.com>
 * @version 1.1.0
 * @property-read \nickcv\encrypter\components\Encrypter $encrypter
 */
class Module extends \yii\base\Module implements BootstrapInterface
{   
    public function init()
    {   
        $configFile = \Yii::getAlias('@app/config/encrypter.php');
        
        if (file_exists($configFile)) {
            $this->setComponents([
               'encrypter' => require($configFile),
            ]);
        }
        parent::init();
    }
    
    
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            
            $this->controllerNamespace = 'nickcv\encrypter\commands';
            $this->setAliases([
                '@nickcv/encrypter' =>  dirname(__FILE__),
            ]);
        }
    }
}