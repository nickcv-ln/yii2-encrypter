<?php
/**
 * Contains the controller class triggered by the ```./yii encrypter/decrypt```
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
 * Decrypt a string using your current encrypter configuration.
 * 
 * @author Nicola Puddu <n.puddu@outlook.com>
 * @version 1.0
 * @since 1.1.0
 * @property-read \nickcv\encrypter\Module $module
 */
class DecryptController extends Controller
{
    /**
     * Decrypts a string using your current encrypter configuration.
     */
    public function actionIndex()
    {
        $decryptedString = $this->getEncrypter()->decrypt($this->prompt("\nType here the string to decrypt:"));
        
        $this->stdout("\nDecrypted String:\n");
        $this->stdout($decryptedString, Console::FG_GREEN);
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
