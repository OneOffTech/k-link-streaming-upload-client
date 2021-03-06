# K-Link Streaming Service client

Contact a [K-Link Video Streaming](https://github.com/k-box/k-link-video-streaming) service via API.

> **Not usable on Alpine linux as requires an executable not compiled for alpine**

## Getting started

### Installation

Require the package with

```bash
composer require php-http/guzzle6-adapter guzzlehttp/psr7 oneofftech/k-link-streaming-upload-client
```

**Why requiring so many packages?**

K-Link Streaming Upload client has a dependency on the virtual package
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) which requires to you install **an** adapter, but we do not care which one. That is an implementation detail in your application. We also need **a** PSR-7 implementation and **a** message factory. 

You do not have to use the `php-http/guzzle6-adapter` if you do not want to. You may use the `php-http/curl-client`. Read more about the virtual packages, why this is a good idea and about the flexibility it brings at the [HTTPlug docs](http://docs.php-http.org/en/latest/httplug/users.html).

**Post install/update/require**

The Streaming Service client depends on the [Tus Cli](https://github.com/avvertix/tus-client-cli), 
which is not included in this repository to keep the size within a reasonable limit. Composer, 
for [various reasons](https://github.com/composer/composer/issues/1193), don't execute 
`post-install` scripts of required packages therefore it needs to be run manually.

You could do it via bash/shell

```bash
composer run-script post-install-cmd -d ./vendor/oneofftech/k-link-streaming-upload-client
```

Or invoke that script from the `post-install-cmd`/`post-update-cmd` scripts defined in the `composer.json`

```json
"scripts": {
    "post-install-cmd": "@composer run-script post-install-cmd -d ./vendor/oneofftech/k-link-streaming-upload-client",
    "post-update-cmd": "@composer run-script post-install-cmd -d ./vendor/oneofftech/k-link-streaming-upload-client"
}
```

### Usage

The client requires a registered application on the [K-Registry](https://github.com/k-box/k-link-registry), as the streaming service will verify that the application has the rights to upload videos.

```php
use Oneofftech\KlinkStreaming\Client;

$streaming_service_url = 'https://streaming.test.klink.asia/';
$application_token = 'Application Token';
$application_url = 'https://myapp.local/';

$client = new Client($streaming_service_url, $application_token, $application_url);

$client->upload($file);
```


## Documentation

There is no full documentation yet, for usage examples you might want to have a look at the [Integration tests](./tests/Integration/ClientUsageTest.php)


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

This project is licensed under the [MIT license](./LICENSE).
