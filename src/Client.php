<?php
/**
 * @licence proprietary
 */
namespace Dnx\Sso;

/**
 * Client
 *
 * @author Joseph LEMOINE <j.lemoine@ludi.cat>
 */
class Client
{
    const API_URL = 'https://api.back.dnx.lu';
    const API_ENDPOINT = '/sso';

    public function __construct(
        protected string $token,
        protected string $baseUrl = self::API_URL,
    ) { }

    public function register(
        string $email,
        string $userIp,
        string $country,
        string $language,
        ?string $model = null,
        ?ServiceEnum $service = null,
        ?string $tracker = null,
    )
    {
        // Call the API with curl
        $url = $this->baseUrl . self::API_ENDPOINT;
        $data = [
            'email' => $email,
            'userIp' => $userIp,
            'country' => $country,
            'language' => $language,
            'model' => $model,
            'service' => $service,
            'tracker' => $tracker,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-AUTH-TOKEN: ' . $this->token,
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return new Response($response);
    }

    public function login(
        string $email,
        string $loginToken,
        string $userIp,
        string $country,
        string $language,
        ?string $model = null,
        ?ServiceEnum $service = null,
        ?string $tracker = null,
    )
    {
        // Call the API with curl
        $url = $this->baseUrl . self::API_ENDPOINT;
        $data = [
            'email' => $email,
            'loginToken' => $loginToken,
            'userIp' => $userIp,
            'country' => $country,
            'language' => $language,
            'model' => $model,
            'service' => $service,
            'tracker' => $tracker,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-AUTH-TOKEN: ' . $this->token,
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return new Response($response);
    }
}
