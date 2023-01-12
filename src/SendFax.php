<?php

use Sipgate\Io\Example\SendFax\Dto\Fax;
use Sipgate\Io\Example\SendFax\SipgateClient;

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$tokenId = $_ENV['TOKEN_ID'];
$token = $_ENV['TOKEN'];
$faxlineId = $_ENV['FAXLINE_ID'];
$recipient = $_ENV['RECIPIENT'];
$filePath = $_ENV['PDF_FILE_PATH'];

$client = new SipgateClient($tokenId, $token);
$fax = new Fax($faxlineId, $recipient, $filePath);

echo "Add fax to the sending queue...\n";

$response = $client->sendFax($fax);

if (!$response->isOk())
{
    exit("Sending fax failed\n");
}

$sessionId = $response->json()["sessionId"];

echo "Fax added to the sending queue.\n";

while (true)
{
    $statusResponse = $client->fetchFaxStatus($sessionId);

    if (!$statusResponse->isOk())
    {
        exit("Could not get fax status.\n");
    }

    $status = $statusResponse->json()["faxStatusType"];

    echo "Checking fax status - " . date("H:i:s") . " " . $status . "\n";

    if ($status == "SENT")
    {
        echo "The Fax was sent successfully.\n";
        break;
    }

    sleep(5);
}

?>
