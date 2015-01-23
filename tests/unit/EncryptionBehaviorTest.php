<?php

use yii\codeception\DbTestCase;
use nickcv\encrypter\tests\codeception\fixtures\ClientFixture;
use nickcv\encrypter\tests\codeception\models\Client;
use nickcv\encrypter\tests\codeception\models\RawClient;

class EncryptionBehaviorTest extends DbTestCase
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'clients' => ClientFixture::className(),
        ];
    }

    protected function _before()
    {
        
    }

    protected function _after()
    {
        
    }

    public function testRetrieveEncryptedDataFromDB()
    {
        $client = Client::find()->where(['id' => 1])->one();
        $this->assertEquals('my name', $client->name);
        $this->assertEquals('my address', $client->address);
        $this->assertEquals('several notes', $client->notes);
        
        $rawClient = RawClient::find()->where(['id' => 1])->one();
        
        $this->assertEquals('RzxyESQLnEdtFLMIaoWYQQ==', $rawClient->name);
        $this->assertEquals('my address', $rawClient->address);
        $this->assertEquals('3j795GU3kLhlYsr2oVYH5w==', $rawClient->notes);
    }
    
    public function testEncryptWhenSavingNewData()
    {
        $client = new Client;
        $client->name = 'new client name';
        $client->address = 'new client address';
        $client->notes = 'new client notes';
        $client->save();
        
        $this->assertEquals('new client name', $client->name);
        $this->assertEquals('new client address', $client->address);
        $this->assertEquals('new client notes', $client->notes);
        
        $retrievedData = Client::find()->where(['id' => 2])->one();
        $this->assertEquals('new client name', $retrievedData->name);
        $this->assertEquals('new client address', $retrievedData->address);
        $this->assertEquals('new client notes', $retrievedData->notes);
        
        
        $rawClient = RawClient::find()->where(['id' => 2])->one();
        
        $this->assertEquals('joQWGrxLmKmaRX4Yt30vFA==', $rawClient->name);
        $this->assertEquals('new client address', $rawClient->address);
        $this->assertEquals('6H5n+hABrPmp6Ll7pRhsR/hqDr69hgLIenjqW0wH2O8=', $rawClient->notes);
    }
    
    public function testEncryptWhenUpdatingData()
    {
        $client = Client::find()->where(['id' => 1])->one();
        $client->name = 'changed name';
        $client->address = 'UK';
        $client->notes = 'new notes';
        $client->save();
        
        $this->assertEquals('changed name', $client->name);
        $this->assertEquals('UK', $client->address);
        $this->assertEquals('new notes', $client->notes);
        
        $retrievedData = Client::find()->where(['id' => 1])->one();
        $this->assertEquals('changed name', $retrievedData->name);
        $this->assertEquals('UK', $retrievedData->address);
        $this->assertEquals('new notes', $retrievedData->notes);
        
        $rawClient = RawClient::find()->where(['id' => 1])->one();
        
        $this->assertEquals('4J7sln072uZQsGQ/mzmGSA==', $rawClient->name);
        $this->assertEquals('UK', $rawClient->address);
        $this->assertEquals('8omf00V+7iYD+nKRGvc8WA==', $rawClient->notes);
    }

}
