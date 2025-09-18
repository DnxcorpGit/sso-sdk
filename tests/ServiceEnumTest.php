<?php
/**
 * @licence proprietary
 */
namespace Dnx\Sso\Tests;

use Dnx\Sso\ServiceEnum;
use PHPUnit\Framework\TestCase;

class ServiceEnumTest extends TestCase
{
    public function testConstants(): void
    {
        $this->assertEquals('profile', ServiceEnum::PROFILE);
        $this->assertEquals('webcamsList', ServiceEnum::WEBCAMS_LIST);
    }

    public function testIsValidWithValidValues(): void
    {
        $this->assertTrue(ServiceEnum::isValid('profile'));
        $this->assertTrue(ServiceEnum::isValid('webcamsList'));
    }

    public function testIsValidWithInvalidValues(): void
    {
        $this->assertFalse(ServiceEnum::isValid('invalid'));
        $this->assertFalse(ServiceEnum::isValid(''));
        $this->assertFalse(ServiceEnum::isValid('Profile')); // Case sensitive
        $this->assertFalse(ServiceEnum::isValid('PROFILE')); // Case sensitive
        $this->assertFalse(ServiceEnum::isValid('webcams_list')); // Different format
        $this->assertFalse(ServiceEnum::isValid('webcams'));
        $this->assertFalse(ServiceEnum::isValid('list'));
    }

    public function testGetValues(): void
    {
        $expected = ['profile', 'webcamsList'];
        $this->assertEquals($expected, ServiceEnum::getValues());
    }

    public function testGetValuesReturnsArray(): void
    {
        $values = ServiceEnum::getValues();
        $this->assertIsArray($values);
        $this->assertCount(2, $values);
    }

    public function testAllConstantsAreInGetValues(): void
    {
        $values = ServiceEnum::getValues();
        
        $this->assertContains(ServiceEnum::PROFILE, $values);
        $this->assertContains(ServiceEnum::WEBCAMS_LIST, $values);
    }

    public function testIsValidUsesGetValues(): void
    {
        // Test that isValid() works with all values from getValues()
        $values = ServiceEnum::getValues();
        
        foreach ($values as $value) {
            $this->assertTrue(ServiceEnum::isValid($value), 
                "Value '{$value}' from getValues() should be valid");
        }
    }

    /**
     * Test that isValid is strict (uses strict comparison)
     */
    public function testIsValidIsStrict(): void
    {
        // These should all be false because isValid uses strict comparison
        $this->assertFalse(ServiceEnum::isValid(0));
        $this->assertFalse(ServiceEnum::isValid(1));
        $this->assertFalse(ServiceEnum::isValid(true));
        $this->assertFalse(ServiceEnum::isValid(false));
        $this->assertFalse(ServiceEnum::isValid(null));
    }

    /**
     * Data provider for invalid values
     */
    public function invalidValuesProvider(): array
    {
        return [
            [''],
            ['invalid'],
            ['Profile'],
            ['PROFILE'],
            ['webcams_list'],
            ['webcams'],
            ['list'],
            ['profile '], // with space
            [' profile'], // with space
            ['profile\n'], // with newline
            ['webcamsList '], // with space
            [' webcamsList'], // with space
        ];
    }

    /**
     * @dataProvider invalidValuesProvider
     */
    public function testIsValidWithInvalidValuesDataProvider(string $invalidValue): void
    {
        $this->assertFalse(ServiceEnum::isValid($invalidValue), 
            "Value '{$invalidValue}' should be invalid");
    }

    /**
     * Data provider for valid values
     */
    public function validValuesProvider(): array
    {
        return [
            [ServiceEnum::PROFILE],
            [ServiceEnum::WEBCAMS_LIST],
            ['profile'],
            ['webcamsList'],
        ];
    }

    /**
     * @dataProvider validValuesProvider
     */
    public function testIsValidWithValidValuesDataProvider(string $validValue): void
    {
        $this->assertTrue(ServiceEnum::isValid($validValue), 
            "Value '{$validValue}' should be valid");
    }
}
