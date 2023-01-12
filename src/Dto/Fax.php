<?php

namespace Sipgate\Io\Example\SendFax\Dto;

class Fax
{
    protected $faxlineId;
    protected $recipient;
    protected $base64Content;
    protected $fileName;

    public function __construct($faxlineId, $recipient, $filePath)
    {
        $this->faxlineId = $faxlineId;
        $this->recipient = $recipient;

        $fileContent = file_get_contents($filePath);
        $this->base64Content = base64_encode($fileContent);
        $this->fileName = basename($filePath);
    }

    public function toArray()
    {
        return [
            "faxlineId" => $this->faxlineId,
            "base64Content" => $this->base64Content,
            "recipient" => $this->recipient,
            "filename" => $this->fileName
        ];
    }
}

?>
