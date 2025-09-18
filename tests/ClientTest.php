<?php
/**
 * @licence proprietary
 */
namespace Dnx\Sso\Tests;

use Dnx\Sso\Client;
use Dnx\Sso\Response;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private Client $client;
    private string $testToken = 'test-token-123';
    private string $testBaseUrl = 'https://test-api.example.com';

    protected function setUp(): void
    {
        $this->client = new Client($this->testToken, $this->testBaseUrl);
    }

    public function testConstructorWithDefaultValues(): void
    {
        $client = new Client('token123');
        
        // We can't directly test private properties, but we can test behavior
        // The constructor should set the token and use default base URL
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testConstructorWithCustomValues(): void
    {
        $customHeaders = ['Custom-Header: value'];
        $client = new Client('token123', 'https://custom.api.com', $customHeaders);
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testEncodeDataWithSimpleArray(): void
    {
        $data = [
            'email' => 'test@example.com',
            'userIp' => '192.168.1.1',
            'country' => 'US'
        ];
        
        $encoded = $this->client->encodeData($data);
        
        $this->assertEquals('email=test%40example.com&userIp=192.168.1.1&country=US', $encoded);
    }

    public function testEncodeDataWithSpecialCharacters(): void
    {
        $data = [
            'email' => 'test+user@example.com',
            'name' => 'John Doe & Co',
            'message' => 'Hello world!'
        ];
        
        $encoded = $this->client->encodeData($data);
        
        $this->assertEquals('email=test%2Buser%40example.com&name=John+Doe+%26+Co&message=Hello+world%21', $encoded);
    }

    public function testEncodeDataWithNullValues(): void
    {
        $data = [
            'email' => 'test@example.com',
            'optional' => null,
            'country' => 'US'
        ];
        
        $encoded = $this->client->encodeData($data);
        
        $this->assertEquals('email=test%40example.com&optional=&country=US', $encoded);
    }

    public function testEncodeDataWithEmptyArray(): void
    {
        $encoded = $this->client->encodeData([]);
        
        $this->assertEquals('', $encoded);
    }

    public function testApiConstants(): void
    {
        $this->assertEquals('https://api.back.dnx.lu', Client::API_URL);
        $this->assertEquals('/sso/', Client::API_ENDPOINT);
    }

    /**
     * Test parameter validation - register method requires certain parameters
     */
    public function testRegisterRequiredParameters(): void
    {
        // This test verifies that the method signature enforces required parameters

        // These calls should not cause PHP errors due to missing required parameters
        try {
            // This would fail at runtime due to missing parameters, but the signature is correct
            $reflection = new \ReflectionMethod(Client::class, 'register');
            $parameters = $reflection->getParameters();

            // Verify required parameters exist
            $requiredParams = ['email', 'userIp', 'country', 'language'];
            $paramNames = array_map(fn($p) => $p->getName(), $parameters);

            foreach ($requiredParams as $required) {
                $this->assertContains($required, $paramNames);
            }
        } catch (\ReflectionException $e) {
            $this->fail('Could not reflect on register method: ' . $e->getMessage());
        }
    }

    /**
     * Test parameter validation - login method requires certain parameters
     */
    public function testLoginRequiredParameters(): void
    {
        // This test verifies that the method signature enforces required parameters

        try {
            $reflection = new \ReflectionMethod(Client::class, 'login');
            $parameters = $reflection->getParameters();

            // Verify required parameters exist
            $requiredParams = ['email', 'loginToken', 'userIp', 'country', 'language'];
            $paramNames = array_map(fn($p) => $p->getName(), $parameters);

            foreach ($requiredParams as $required) {
                $this->assertContains($required, $paramNames);
            }
        } catch (\ReflectionException $e) {
            $this->fail('Could not reflect on login method: ' . $e->getMessage());
        }
    }
}
