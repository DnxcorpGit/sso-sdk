<?php
/**
 * @licence proprietary
 */
namespace Dnx\Sso;

class Response
{
    protected ?string $redirectUrl = null;
    protected ?string $loginToken = null;
    protected ?string $reason = null;
    protected bool $canFetchUrl = false;
    protected ?int $statusCode;
    protected ?string $data;

    public function __construct(
        ?int $statusCode,
        ?string $data
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        if ($this->statusCode !== 200) {
            $this->reason = sprintf('HTTP status code is %s', $this->statusCode);
            return;
        }

        $decoded = json_decode($data, true);

        $this->redirectUrl = $decoded['redirectUrl'];
        $this->loginToken = $decoded['loginToken'];
        $this->canFetchUrl = empty($decoded['loginToken']);
        $this->reason = $decoded['reason'];
    }

    public function isSuccess(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function getRedirectUrl(): ?string
    {
        if (!$this->canFetchUrl) {
            throw new \LogicException('You cannot fetch the URL before retrieving the login token.');
        }
        
        return $this->redirectUrl;
    }

    public function getLoginToken(): ?string
    {
        $this->canFetchUrl = true;

        return $this->loginToken;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}