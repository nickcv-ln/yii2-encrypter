<?php

use yii\codeception\TestCase;
use nickcv\encrypter\components\Encrypter;

class EncrypterTest extends TestCase
{
    protected $tester;
    
    protected function before()
    {
    }

    protected function after()
    {
    }

    public function testGlobalPasswordIsRequired()
    {
		$this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::globalPassword" cannot be null.');
		new Encrypter();
    }
    
    public function testGlobalPasswordMustBeString()
	{
		$this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::globalPassword" should be a string, "array" given.');
		new Encrypter(['globalPassword'=>array()]);
	}
	
	public function testGlobalPasswordLengthGreaterThanZero()
	{
		$this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::globalPassword" length should be greater than 0.');
		new Encrypter(['globalPassword'=>' ']);
	}
    
    public function testIvIsRequired()
    {
		$this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::iv" cannot be null.');
		new Encrypter(['globalPassword'=>'test']);
    }
    
    public function testIvMustBeString()
	{
		$this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::iv" should be a string, "array" given.');
		new Encrypter(['globalPassword'=>'test','iv'=>array()]);
	}
	
	public function testIvLengthGreaterThanZero()
	{
		$this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::iv" length should be greater than 0.');
		new Encrypter(['globalPassword'=>'test','iv'=>' ']);
	}
    
    public function testIvNot16Bytes()
	{
		$this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::iv" should be exactly 16 bytes long, 4 given.');
		new Encrypter(['globalPassword'=>'test','iv'=>'test']);
	}
    
    public function testUseBase64EncodingMustBeBoolean()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::useBase64Encoding" should be a boolean, "string" given.');
		new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>'false',
            ]);
    }
    
    public function testUse256BitesEncodingMustBeBoolean()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', '"nickcv\encrypter\components\Encrypter::use256BitesEncoding" should be a boolean, "string" given.');
		new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>true,
            'use256BitesEncoding'=>'false',
            ]);
    }
    
    public function testEncryptStringWith128Encoding()
    {
        $encrypter = new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>false,
            'use256BitesEncoding'=>false,
            ]);
        
        $this->assertEquals(hex2bin('a3ffeca54bdc302a8fb807a797f294a3'), $encrypter->encrypt('test string.'));
    }
    
    public function testEncryptStringWith256Encoding()
    {
        $encrypter = new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>false,
            'use256BitesEncoding'=>true,
            ]);
        
        $this->assertEquals(hex2bin('2198dfce021fb27582af655177b08f46'), $encrypter->encrypt('test string.'));
    }
    
    public function testEncryptStringWith128AndBase64Encoding()
    {
        $encrypter = new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>true,
            'use256BitesEncoding'=>false,
            ]);
        
        $this->assertEquals('o//spUvcMCqPuAenl/KUow==', $encrypter->encrypt('test string.'));
    }
    
    public function testEncryptStringWith256AndBase64Encoding()
    {
        $encrypter = new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>true,
            'use256BitesEncoding'=>true,
            ]);
        
        $this->assertEquals('IZjfzgIfsnWCr2VRd7CPRg==', $encrypter->encrypt('test string.'));
    }
    
    public function testDecryptStringWith128Encoding()
    {
        $encrypter = new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>false,
            'use256BitesEncoding'=>false,
            ]);
        
        $this->assertEquals('test string.', $encrypter->decrypt(hex2bin('a3ffeca54bdc302a8fb807a797f294a3')));
    }
    
    public function testDecryptStringWith256Encoding()
    {
        $encrypter = new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>false,
            'use256BitesEncoding'=>true,
            ]);
        
        $this->assertEquals('test string.', $encrypter->decrypt(hex2bin('2198dfce021fb27582af655177b08f46')));
    }
    
    public function testDecryptStringWith128AndBase64Encoding()
    {
        $encrypter = new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>true,
            'use256BitesEncoding'=>false,
            ]);
        
        $this->assertEquals('test string.', $encrypter->decrypt('o//spUvcMCqPuAenl/KUow=='));
    }
    
    public function testDecryptStringWith256AndBase64Encoding()
    {
        $encrypter = new Encrypter([
            'globalPassword'=>'test',
            'iv'=>'1234567890123456',
            'useBase64Encoding'=>true,
            'use256BitesEncoding'=>true,
            ]);
        
        $this->assertEquals('test string.', $encrypter->decrypt('IZjfzgIfsnWCr2VRd7CPRg=='));
    }

}