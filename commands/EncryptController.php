<?php
/**
 * Contains the controller class triggered by the ```./yii encrypter/encrypt```
 * console command.
 * 
 * @link http://www.creationgears.com/
 * @copyright Copyright (c) 2015 Nicola Puddu
 * @license http://www.gnu.org/copyleft/gpl.html
 * @package nickcv/yii2-encrypter
 * @author Nicola Puddu <n.puddu@outlook.com>
 */

namespace nickcv\encrypter\commands;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Encrypt a string using your current encrypter configuration.
 * 
 * @author Nicola Puddu <n.puddu@outlook.com>
 * @version 1.0
 * @since 1.1.0
 * @property-read \nickcv\encrypter\Module $module
 */
class EncryptController extends Controller
{
    /**
     * Encrypts a string using your current encrypter configuration.
     */
    public function actionIndex()
    {
        $encryptedString = $this->getEncrypter()->encrypt($this->prompt("\nType here the string to encrypt:"));
        
        $this->stdout("\nEncrypted String:\n");
        $this->stdout($encryptedString, Console::FG_GREEN);
        $this->stdout("\n\n");
    }
    
    /**
     * Returns the current instance of the encrypter component.
     * 
     * @return \nickcv\encrypter\components\Encrypter
     */
    private function getEncrypter()
    {
        try {
            return $this->module->encrypter;
            
        } catch (\Exception $exc) {
            $this->stdout("The encrypter configuration file \"encrypter.php\" was not found in your config directory.\n", Console::FG_RED);
        }
    }
    
}
