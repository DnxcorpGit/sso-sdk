<?php
/**
 * @licence proprietary
 */
namespace Dnx\Sso;

class Client
{
    const API_URL = 'https://api.back.dnx.lu';
    const API_ENDPOINT = '/sso/';

    protected string $token;
    protected string $baseUrl;
    protected array $headers;

    public function __construct(
        string $token,
        string $baseUrl = self::API_URL,
        array $headers = []
    ) {
        $this->token = $token;
        $this->baseUrl = $baseUrl;
        $this->headers = $headers;
    }

    public function register(
        string $email,
        string $userIp,
        string $country,
        string $language,
        ?string $model = null,
        ?string $service = null,
        ?string $tracker = null,
    )
    {
        // Call the API with curl
        $url = $this->baseUrl . self::API_ENDPOINT;
        $data = [
            'email' => $email ?: null,
            'userIp' => $userIp ?: null,
            'country' => $country ?: null,
            'language' => $language ?: null,
            'model' => $model ?: null,
            'service' => $service ?: null,
            'tracker' => $tracker ?: null,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->encodeData($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($this->headers, [
            'X-AUTH-TOKEN: ' . $this->token,
            'Content-Type: application/x-www-form-urlencoded',
        ]));

        $response = curl_exec($ch);
        // Get http response
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return new Response($httpCode, $response);
    }

    public function login(
        string $email,
        string $loginToken,
        string $userIp,
        string $country,
        string $language,
        ?string $model = null,
        ?string $service = null,
        ?string $tracker = null,
    )
    {
        // Call the API with curl
        $url = $this->baseUrl . self::API_ENDPOINT;
        $data = [
            'email' => $email ?: null,
            'loginToken' => $loginToken ?: null,
            'userIp' => $userIp ?: null,
            'country' => $country ?: null,
            'language' => $language ?: null,
            'model' => $model ?: null,
            'service' => $service ?: null,
            'tracker' => $tracker ?: null,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->encodeData($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($this->headers, [
            'X-AUTH-TOKEN: ' . $this->token,
            'Content-Type: application/x-www-form-urlencoded',
        ]));

        $response = curl_exec($ch);
        // Get http response
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return new Response($httpCode, $response);
    }

    public function encodeData(array $data): string
    {
        // make data as url encoded string
        $vars = [];
        foreach ($data as $key => $value) {
            $vars[] = $key . '=' . urlencode($value);
        }

        return implode('&', $vars);
    }
}
