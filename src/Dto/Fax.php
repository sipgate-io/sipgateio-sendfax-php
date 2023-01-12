<?php

namespace Sipgate\Io\Example\SendFax\Dto;

class Fax
{
    protected $faxlineId;
    protected $base64Content;
    protected $recipient;
    protected $fileName;

    public function __construct($faxlineId, $base64Content, $recipient, $fileName)
    {
        $this->faxlineId = $faxlineId;
        $this->base64Content = $base64Content;
        $this->recipient = $recipient;
        $this->fileName = $fileName;
    }

    public function toArray()
    {
        return [
            "faxlineId" => $this->faxlineId,
            "base64Content" => $this->base64Content,
            "recipient" => $this->recipient,
            "fileName" => $this->fileName
        ];
    }
}

?>
