<?php
/**
 * @licence proprietary
 */
namespace Dnx\Sso;

class ServiceEnum
{
    public const PROFILE = 'profile';
    public const WEBCAMS_LIST = 'webcamsList';

    /**
     * Validate if a value is a valid service enum value
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, [self::PROFILE, self::WEBCAMS_LIST], true);
    }

    /**
     * Get all valid values
     */
    public static function getValues(): array
    {
        return [self::PROFILE, self::WEBCAMS_LIST];
    }
}
