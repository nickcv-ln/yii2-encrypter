<?php
/**
 * Contains the component used for encrypting and decrypting data.
 * 
 * @link http://www.creationgears.com/
 * @copyright Copyright (c) 2015 Nicola Puddu
 * @license http://www.gnu.org/copyleft/gpl.html
 * @package nickcv/yii2-encrypter
 * @author Nicola Puddu <n.puddu@outlook.com>
 */

namespace nickcv\encrypter\components;

use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Encrypter is the class that is used to encrypt and decrypt the data.
 * 
 * @author Nicola Puddu <n.puddu@outlook.com>
 * @version 1.0
 */
class Encrypter extends Component
{    
    /**
     * 128 bites cypher method used by the openssl functions.
     */
    const AES128 = 'aes-128-cbc';
    /**
     * 256 bites cypher method used by the openssl functions.
     */
	const AES256 = 'aes-256-cbc';
    /**
     * Size in bites of the IV required by the cypher methods.
     */
    const IV_LENGTH = 16;
    
    /**
     * Contains the global password used to encrypt and decrypt.
     * 
     * @var string
     */
    private $_globalPassword;
    /**
     * Contains the IV used to encrypt and decrypt.
     *
     * @var string
     */
    private $_iv;
    /**
     * Boolean value to indicate whether the extension should use 128 bites or
     * 256 bites encryption.
     *
     * @var boolean
     */
    private $_use256BitesEncoding = false;
    /**
     * Boolean value to indicate whether the extension should use base64
     * encoding after encrypting and before decrypting a string.
     *
     * @var boolean
     */
    private $_useBase64Encoding = false;
    
    /**
     * Checks that the globalPassword and iv have indeed been set.
     * 
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->_globalPassword) {
            throw new InvalidConfigException('"' . get_class($this) . '::globalPassword" cannot be null.');
        }
        
        if (!$this->_iv) {
            throw new InvalidConfigException('"' . get_class($this) . '::iv" cannot be null.');
        }
    }
    
    /**
     * Sets the global password for the encrypter
     * 
     * @param string $globalPassword the global password
     */
    public function setGlobalPassword($globalPassword)
    {
        $this->_globalPassword = $this->getPurifiedString($globalPassword, 'globalPassword');
    }
    
    /**
     * Sets the iv for the encrypter
     * 
     * @param string $iv the iv
     */
    public function setIv($iv)
    {
       $purifiedIV = $this->getPurifiedString($iv, 'iv');
       
       if (strlen($purifiedIV) !== self::IV_LENGTH) {
           throw new InvalidConfigException('"' . get_class($this) . '::iv" should be exactly ' . self::IV_LENGTH . ' bytes long, ' . strlen($purifiedIV) . ' given.');
       }
       
       $this->_iv = $purifiedIV;
    }
    
    /**
     * Sets whether the encrypter should use the 256 bites Cypher method instead
     * of the 128 bites Cypher method.
     * 
     * @param boolean $use256BitesEncoding
     */
    public function setUse256BitesEncoding($use256BitesEncoding)
    {
        $this->checkBoolean($use256BitesEncoding, 'use256BitesEncoding');
        
        $this->_use256BitesEncoding = $use256BitesEncoding;
    }
    
    /**
     * Sets whether the encrypter should use base64 encoding after encrypting
     * a string.
     * 
     * @param boolean $useBase64Encoding
     */
    public function setUseBase64Encoding($useBase64Encoding)
    {
        $this->checkBoolean($useBase64Encoding, 'useBase64Encoding');
        
        $this->_useBase64Encoding = $useBase64Encoding;
    }
    
    /**
     * Encrypts a string.
     * 
     * @param string $string the string to encrypt
     * @return string the encrypted string
     */
    public function encrypt($string)
    {
        $encryptedString = openssl_encrypt($string, $this->getCypherMethod(), $this->_globalPassword, true, $this->_iv);
        
        if ($this->_useBase64Encoding) {
            $encryptedString = base64_encode($encryptedString);
        }
        
        return $encryptedString;
    }
    
    /**
     * Decrypts a string. 
     * False is returned in case it was not possible to decrypt it.
     * 
     * @param string $string the string to decrypt
     * @return string the decrypted string
     */
    public function decrypt($string)
    {
        $decodedString = $string;
        
        if ($this->_useBase64Encoding) {
            $decodedString = base64_decode($decodedString);
        }
        
        return openssl_decrypt($decodedString, $this->getCypherMethod(), $this->_globalPassword, true, $this->_iv);;
    }
    
    /**
     * Checks whether the value is a string and not empty, returning the trimmed
     * version of it.
     * 
     * @param string $value
     * @param string $propertyName
     * @return string trimmed value
     * @throws InvalidConfigException
     */
    private function getPurifiedString($value, $propertyName)
    {
        if (!is_string($value)) {
            throw new InvalidConfigException('"' . get_class($this) . '::' . $propertyName . '" should be a string, "' . gettype($value) . '" given.');
        }
        
        $trimmedValue = trim($value);

        if (!strlen($trimmedValue) > 0) {
            throw new InvalidConfigException('"' . get_class($this) . '::' . $propertyName . '" length should be greater than 0.');
        }
        
        return $trimmedValue;
    }
    
    /**
     * Checks whether the value is a boolean returning it.
     * 
     * @param boolean $value
     * @param string $propertyName
     * @throws InvalidConfigException
     */
    private function checkBoolean($value, $propertyName)
    {
        if (!is_bool($value)) {
            throw new InvalidConfigException('"' . get_class($this) . '::' . $propertyName . '" should be a boolean, "' . gettype($value) . '" given.');
        }
    }
    
    /**
     * Returns the cypher method based on the current configuration.
     * 
     * @return string the cypher method
     */
    private function getCypherMethod()
    {
        if ($this->_use256BitesEncoding) {
            return self::AES256;
        }
        
        return self::AES128;
    }
}
