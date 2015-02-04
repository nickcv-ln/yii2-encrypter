<?php
/**
 * Contains the controller class triggered by the ```./yii encrypter/setup```
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
use yii\web\View;
use yii\helpers\Console;

/**
 * Automatically generate the config file used by the encrypter component.
 * 
 * @author Nicola Puddu <n.puddu@outlook.com>
 * @version 1.1.0
 */
class SetupController extends Controller
{
    /**
     * This action generates the configuration file for the encrypter component
     * inside the config directory.
     */
    public function actionIndex()
    {
        $configFile = \Yii::getAlias('@app').DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'encrypter.php';
        file_put_contents($configFile, $this->getConfigFileContent());
        $this->stdout("\nconfig file generated in '$configFile'.", Console::FG_GREEN);
        $this->stdout("\nadd this line in your web config file inside the components array:");
        echo $this->ansiFormat("\n\t'encrypter' => require(__DIR__ . DIRECTORY_SEPARATOR . 'encrypter.php'),\n", Console::BOLD, Console::FG_PURPLE);
    }
    
    /**
     * Returns the content of the config file that will be generated in the
     * config directory.
     * 
     * @return string the config file content
     */
    private function getConfigFileContent()
    {
        $view = new View();
        return $view->renderFile(__DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'config.php', [
            'password'=>$this->getRandomPassword(),
            'iv'=>$this->getRandomPassword(\nickcv\encrypter\components\Encrypter::IV_LENGTH),
        ], $this);
    }
    
    /**
     * Returns a randomly generated string with uppercase letter, lowercase
     * letters, numbers and special characters.
     * 
     * @param integer $length length of the randomly generated string
     * @return string random string
     */
    private function getRandomPassword($length = 12)
    {
        $stringWithNoSpecialChars = substr(str_shuffle(MD5(microtime())), 0, $length - 3);
        
        return str_shuffle(str_shuffle($stringWithNoSpecialChars.$this->getSpecialCharacters()));
    }
    
    /**
     * Returns a random selection of 3 special characters
     * 
     * @return string 3 special characters
     */
    private function getSpecialCharacters()
    {
        $specialCharacters = '!-_?.:;,/';
        
        return substr(str_shuffle($specialCharacters), 0, 3);   
    }
}
