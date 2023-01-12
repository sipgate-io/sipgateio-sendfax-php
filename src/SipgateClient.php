<?php

namespace Sipgate\Io\Example\SendFax;

use Sipgate\Io\Example\SendFax\Dto\Fax;
use Zttp\Zttp;
use Zttp\ZttpResponse;

class SipgateClient
{
    protected static $BASE_URL = "https://api.sipgate.com/v2";

    protected $tokenId;
    protected $token;

    public function __construct(string $tokenId, string $token)
    {
        $this->tokenId = $tokenId;
        $this->token = $token;
    }

    public function sendFax(Fax $fax): ZttpResponse
    {
        return Zttp::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ])
            ->withBasicAuth($this->tokenId, $this->token)
            ->post(self::$BASE_URL . "/sessions/fax", $fax->toArray());
    }

    public function fetchFaxStatus(string $sessionId): ZttpResponse
    {
        return Zttp::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ])
        ->withBasicAuth($this->tokenId, $this->token)
        ->get(self::$BASE_URL . "/history/" . $sessionId);
    }
}

?>
