<?php

/**
 * @licence proprietary
 */

namespace Dnx\Sso;

/**
 * Response
 *
 * @author Joseph LEMOINE <j.lemoine@ludi.cat>
 */
class Response
{
    protected string $redirectUrl;
    protected string $loginToken;
    protected bool $canFetchUrl = false;

    public function __construct(
        string $json
    ) {
        $data = json_decode($json, true);

        $this->redirectUrl = $data['redirectUrl'];
        $this->loginToken = $data['loginToken'];
        $this->canFetchUrl = empty($data['loginToken']);
        $this->reason = $data['reason'];
    }

    public function isSuccess(): bool
    {
        return empty($this->reason);
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function getLoginToken(): string
    {
        $this->canFetchUrl = true;

        return $this->loginToken;
    }
}