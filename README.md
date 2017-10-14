# K-Link Streaming Service client

Contact a K-Link Video Streaming service via API.

> The client is in development, API and classes are subject to change.

> **Not usable on Alpine linux as requires an executable not compiled for alpine**

## Getting started

### Installation

As the package is still in development we require to add a repository entry in your 
`composer.json` file before you can pull in the package:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://git.klink.asia/main/k-link-video-streaming-client-php.git"
    }
]
```

Now you can add the reference to the package in your `composer.json` require section

```json
"require": {
    "oneofftech/k-link-streaming-upload-client": "dev-master"
},
```

And finally pull in the source

```
composer update oneofftech/k-link-streaming-upload-client
```

### Usage

> The client requires that your project has a registered application on the K-Registry, as the
> streaming service will verify that the application has the right permission to upload videos.

```php
use Oneofftech\KlinkStreaming\Client;

$streaming_service_url = 'https://streaming.test.klink.asia/';
$application_token = 'Application Token';
$application_url = 'https://myapp.local/';

$client = new Client($streaming_service_url, $application_token, $application_url);

$client->upload($file);
```


## Documentation

_to be written_


## Testing

The code testing is automated using [PHPUnit](https://phpunit.de/).

There are 3 testing suites:

- `Unit`: test classes in isolation
- `Feature`: test the features of the Client class using mocked responses
- `Integration`: test the features using a real K-Link Video Streaming service

The tests can be executed using

```bash
vendor/bin/phpunit
```

**Executing integration tests**

Integration tests requires to set the `VIDEO_STREAMING_SERVICE_URL` environment variable to the URL of a running K-Link Video Streaming service.

Leaving the `VIDEO_STREAMING_SERVICE_URL` variable empty will cause the integration tests to be skipped. The available `phpunit.xml.dist` already have that variable, you only need to copy `phpunit.xml.dist` to `phpunit.xml` and execute it.


## Contributing

Thank you for considering contributing to the K-Link Streaming Service PHP Client! The contribution guide is not available yet, but in the meantime you can still submit Pull Requests.

## License

_needs to be selected_

