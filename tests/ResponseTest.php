<?php
/**
 * @licence proprietary
 */
namespace Dnx\Sso\Tests;

use Dnx\Sso\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testConstructorWithSuccessfulResponse(): void
    {
        $data = json_encode([
            'redirectUrl' => 'https://example.com/redirect',
            'loginToken' => 'token123',
        ]);
        
        $response = new Response(200, $data);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data, $response->getData());
        $this->assertTrue($response->isSuccess());
        $this->assertNull($response->getReason());
    }

    public function testConstructorWithErrorResponse(): void
    {
        $response = new Response(404, 'Not Found');
        
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', $response->getData());
        $this->assertFalse($response->isSuccess());
        $this->assertEquals('HTTP status code is 404', $response->getReason());
    }

    public function testConstructorWithNullValues(): void
    {
        $response = new Response(null, null);
        
        $this->assertNull($response->getStatusCode());
        $this->assertNull($response->getData());
        $this->assertFalse($response->isSuccess());
        $this->assertNotNull($response->getReason());
    }

    public function testIsSuccessWithVariousStatusCodes(): void
    {
        $testCases = [
            [200, true],
            [201, true],
            [299, true],
            [300, false],
            [404, false],
            [500, false],
            [199, false],
            [100, false]
        ];

        foreach ($testCases as [$statusCode, $expected]) {
            $response = new Response($statusCode, '{}');
            $this->assertEquals($expected, $response->isSuccess(), 
                "Status code {$statusCode} should return " . ($expected ? 'true' : 'false'));
        }
    }

    public function testGetRedirectUrlWhenCanFetchUrl(): void
    {
        $data = json_encode([
            'redirectUrl' => 'https://example.com/redirect',
            'loginToken' => null // This makes canFetchUrl = true
        ]);
        
        $response = new Response(200, $data);
        
        $this->assertEquals('https://example.com/redirect', $response->getRedirectUrl());
    }

    public function testGetRedirectUrlThrowsExceptionWhenCannotFetchUrl(): void
    {
        $data = json_encode([
            'redirectUrl' => 'https://example.com/redirect',
            'loginToken' => 'token123' // This makes canFetchUrl = false initially
        ]);
        
        $response = new Response(200, $data);
        
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You cannot fetch the URL before retrieving the login token.');
        
        $response->getRedirectUrl();
    }

    public function testGetLoginTokenEnablesUrlFetching(): void
    {
        $data = json_encode([
            'redirectUrl' => 'https://example.com/redirect',
            'loginToken' => 'token123'
        ]);
        
        $response = new Response(200, $data);
        
        // Initially cannot fetch URL
        $this->expectException(\LogicException::class);
        $response->getRedirectUrl();
    }

    public function testGetLoginTokenAndThenGetRedirectUrl(): void
    {
        $data = json_encode([
            'redirectUrl' => 'https://example.com/redirect',
            'loginToken' => 'token123'
        ]);
        
        $response = new Response(200, $data);
        
        // Get login token first
        $token = $response->getLoginToken();
        $this->assertEquals('token123', $token);
        
        // Now we can get redirect URL
        $url = $response->getRedirectUrl();
        $this->assertEquals('https://example.com/redirect', $url);
    }

    public function testJsonDecodingWithInvalidJson(): void
    {
        $response = new Response(200, 'invalid json');
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('invalid json', $response->getData());
        $this->assertNull($response->getLoginToken());
        $this->assertNull($response->getRedirectUrl());
        $this->assertEquals('invalid json', $response->getReason());
    }

    public function testJsonDecodingWithPartialData(): void
    {
        $data = json_encode([
            'redirectUrl' => 'https://example.com/redirect'
            // Missing loginToken and reason
        ]);
        
        $response = new Response(200, $data);
        
        $this->assertEquals('https://example.com/redirect', $response->getRedirectUrl());
        $this->assertNull($response->getLoginToken());
        $this->assertNull($response->getReason());
    }

    public function testMappingLogicInConstructor(): void
    {
        $data = json_encode([
            'redirectUrl' => 'https://example.com/redirect',
            'loginToken' => 'token123',
            'reason' => 'some reason'
        ]);
        
        $response = new Response(200, $data);

        $this->assertEquals('token123', $response->getLoginToken());
        $this->assertEquals('some reason', $response->getReason());
    }

    public function testCanFetchUrlLogic(): void
    {
        // When loginToken is empty/null, canFetchUrl should be true
        $data1 = json_encode(['loginToken' => null]);
        $response1 = new Response(200, $data1);
        $this->assertNull($response1->getRedirectUrl()); // Should not throw exception
        
        // When loginToken has value, canFetchUrl should be false initially
        $data2 = json_encode(['loginToken' => 'token123']);
        $response2 = new Response(200, $data2);
        
        $this->expectException(\LogicException::class);
        $response2->getRedirectUrl();
    }
}
