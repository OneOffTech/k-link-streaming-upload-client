# K-Link Streaming Service client

Contact a K-Link Video Streaming service via API.

> The client is in development, API and classes are subject to change.

> **Not usable on Alpine linux as requires an executable not compiled for alpine**

## Getting started

### Installation

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://git.klink.asia/alessio.vertemati/k-link-video-streaming-client-php"
    }
]
```

```json
"require": {
    "oneofftech/k-link-streaming-upload-client": "dev-master"
},
```

```
composer update oneofftech/k-link-streaming-upload-client
```

### Usage

```php
use Oneofftech\KlinkStreaming\Client;

$client = new Client($streaming_service_url, $application_token, $application_url);

$client->upload($file);
```


## Documentation

_to be written_


## Contributing

Thank you for considering contributing to the K-Link Streaming Service PHP Client! The contribution guide is not available yet, but in the meantime you can still submit Pull Requests.

## License

_needs to be selected_

