# Pricemotion SDK

This SDK is used internally for Pricemotion's integrations with different
e-commerce systems. It may also be used by customers to integrate with custom or
unsupported systems.

Currently this SDK does not provide a complete interface for Pricemotion's API.
What it does contain is logic for handling webhook requests, and for processing
the XML returned by the Pricemotion API.

Links:

- [API documentation][phpdoc]

## Getting started

Installation is easy, whether you use Composer or not.

### With Composer

Run:

    composer require pricemotion/sdk

Make sure that `vendor/autoload.php` is `require`d in your scripts.

### Without Composer

Download the source code from the [Releases page on GitHub][rel].

Extract it.

Require `autoload.php` in your scripts.

### Dependency injection

If you use a framework that supports automatic dependency injection (autowiring)
such as Symfony, or have manually integrated a dependency container such as
PHP-DI in your project, you should be able to use it to automatically initialize
most of the classes in this SDK.

## Handling webhooks

A cache is required for storing Pricemotion's public keys, so that they do not
have to retrieved on each request. Any cache that implements [Symfony's cache
contract][symfcache] is supported.

For example, you might install `symfony/cache` using Composer and initialize it
as follows:

```php
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$cache = new FilesystemAdapter();
```

With the cache ready, you can decode incoming webhook requests as follows:

```php
use Pricemotion\Sdk\Crypto\SignatureVerifier;
use Pricemotion\Sdk\Api\WebhookRequestFactory;

$body = file_get_contents('php://input');

$signatureVerifier = new SignatureVerifier($cache);
$webhookRequestFactory = new WebhookRequestFactory($signatureVerifier);
$webhookRequest = $webhookRequestFactory->createFromRequestBody($body);
$product = $webhookRequest->getProduct();
```

## Using product data

Getting the EAN:

```php
$product->getEan()->toString();
```

Getting the product name:

```php
$product->getName();
```

Getting the price statistics:

```php
$product->getLowestPrice();
$product->getAveragePrice();
$product->getMedianPrice();
$product->getHighestPrice();
```

Getting the offers ranked from lowest to highest price:

```php
foreach ($product->getOffers() as $offer) {
  $offer->getSeller();
  $offer->getPrice();
}
```

[rel]: https://github.com/pricemotion/sdk/releases
[symfcache]: https://symfony.com/doc/current/components/cache.html
[phpdoc]: https://pricemotion.github.io/sdk/doc/namespaces/pricemotion-sdk.html
