<img src="https://www.sipgatedesign.com/wp-content/uploads/wort-bildmarke_positiv_2x.jpg" alt="sipgate logo" title="sipgate" align="right" height="112" width="200"/>

# sipgate.io php send fax example

To demonstrate how to send a Fax, we queried the `/sessions/fax` endpoint of the sipgate REST API.

For further information regarding the sipgate REST API please visit https://api.sipgate.com/v2/doc

### Prerequisites

- [composer](https://getcomposer.org)
- php >= 7.0

### How to use

Navigate to the project's root directory.

Install dependencies manually or use your IDE's import functionality:

```bash
$ composer install
```

Create the `.env` by copying the [`.env.example`](.env.example) and set the values according to the comment above each variable.

The token should have the `sessions:fax:write` and `history:read` scopes. For more information about personal access tokens visit https://www.sipgate.io/rest-api/authentication#personalAccessToken.

The `FAXLINE_ID` uniquely identifies the extension from which you wish to send your message. Further explanation is given in the section [Web Fax Extensions](#web-fax-extensions).

### Run the application

Install dependencies

```bash
$ composer install
```

Run the application:

```bash
$ php -f src/SendFax.php
```

### How it works

The sipgate REST API is available under the following base URL:

```php
protected static $BASE_URL = "https://api.sipgate.com/v2/";
```

The API expects request data in JSON format. Thus the `Content-Type` header needs to be set accordingly. You can achieve that by using the `withHeaders` method from the `Zttp` library.

```php
public function sendFax(Fax $fax): ZttpResponse
{
    return Zttp::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ])
        ->withBasicAuth($this->tokenId, $this->token)
        ->post(self::$BASE_URL . "/sessions/fax", $fax->toArray());
}
```

The request body contains the `Fax` object, which has four fields: `faxlineId`, `recipient`, `base64Content` and `fileName` specified above. The constructor only expects `faxlineId`, `recipient` and a `filePath`, loading the content and name of the file in the process.

```php
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

    ...

}
```

We use the package `Zttp` for request generation and execution. The `post` method takes the request URL and the requests body payload as arguments. Headers and authorization header are generated from `withHeaders` and `withBasicAuth` methods respectively. The request URL consists of the base URL defined above and the endpoint `/sessions/fax`. The method `withBasicAuth` from the `Zttp` package takes credentials and generates the required Basic Auth header (for more information on Basic Auth see our [sipgate.io PHP PAT example](https://github.com/sipgate-io/sipgateio-personalaccesstoken-php)).

> If OAuth should be used for `Authorization` instead of Basic Auth we do not use the `withBasicAuth(tokenId, token)` method. Instead we set the authorization header to `Bearer` followed by a space and the access token: `Zttp::withHeaders(["Authorization" => "Bearer " . accessToken])`. For an example application interacting with the sipgate API using OAuth see our [sipgate.io PHP OAuth example](https://github.com/sipgate-io/sipgateio-oauth-php).

### Fax Extensions

A Fax extension consists of the letter 'f' followed by a number (e.g. 'f0'). The sipgate API uses the concept of Fax extensions to identify devices within your account that are enabled to send Fax. In this context the term 'device' does not necessarily refer to a hardware Fax but rather a virtual representation.

You can find out what your Fax extension is as follows:

1. Log into your [sipgate account](https://app.sipgate.com/w0/routing)
2. Use the sidebar to navigate to the **Routing** (_Telefonie_) tab
3. Click on any **Fax** device in your routing table
4. Select any option (gear icon) to open the corresponding menu
5. The URL of the page should have the form `https://app.sipgate.com/w0/routing/dialog/{option}/{faxlineId}` where `{faxlineId}` is your Fax extension.

### Common Issues

#### Fax added to the sending queue, but sending failed

Possible reasons are:

- PDF file not encoded correctly in base64
- PDF file with text fields or forms are not supported
- PDF file is corrupt

#### HTTP Errors

| reason                                                                                                                                                | errorcode |
| ----------------------------------------------------------------------------------------------------------------------------------------------------- | :-------: |
| bad request (e.g. request body fields are empty or only contain spaces, timestamp is invalid etc.)                                                    |    400    |
| tokenId and/or token are wrong                                                                                                                        |    401    |
| your account balance is insufficient                                                                                                                  |    402    |
| no permission to use specified Fax extension (e.g. Fax feature not booked or user password must be reset in [web app](https://app.sipgate.com/login)) |    403    |
| wrong REST API endpoint                                                                                                                               |    404    |
| wrong request method                                                                                                                                  |    405    |
| invalid recipient fax number                                                                                                                          |    407    |
| wrong or missing `Content-Type` header with `application/json`                                                                                        |    415    |
| internal server error or unhandled bad request                                                                                                        |    500    |

### Related

- [sipgate team FAQ (DE)](https://help.sipgate.de/hc/de)

### Contact Us

Please let us know how we can improve this example.
If you have a specific feature request or found a bug, please use **Issues** or fork this repository and send a **pull request** with your improvements.

### License

This project is licensed under **The Unlicense** (see [LICENSE file](./LICENSE)).

### External Libraries

This code uses the following external libraries

- Zttp:
  - Licensed under the [MIT License](https://opensource.org/licenses/MIT)
  - Website: [https://github.com/kitetail/zttp](https://github.com/kitetail/zttp)

---

[sipgate.io](https://www.sipgate.io) | [@sipgateio](https://twitter.com/sipgateio) | [API-doc](https://api.sipgate.com/v2/doc)
