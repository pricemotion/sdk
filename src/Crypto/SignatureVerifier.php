<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Crypto;

use Pricemotion\Sdk\RuntimeException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SignatureVerifier {
    private const SIGNING_KEYS_CACHE = 'pricemotion.signing_keys';

    private CacheInterface $cache;

    public function __construct(CacheInterface $cache) {
        $this->cache = $cache;
    }

    public function open(string $body): string {
        $body = base64_decode($body);
        $publicKeys = $this->getPublicKeys();
        foreach ($publicKeys as $publicKey) {
            $result = sodium_crypto_sign_open($body, base64_decode($publicKey));
            if ($result !== false) {
                return $result;
            }
        }
        throw new InvalidMessageException('Message signature could not be verified');
    }

    public function getPublicKeys(): array {
        $overrideFile = @getenv('PRICEMOTION_SDK_SIGNATURES_FILE');
        if ($overrideFile && @file_exists($overrideFile)) {
            return require $overrideFile;
        }
        return $this->cache->get(self::SIGNING_KEYS_CACHE, function (ItemInterface $item) {
            $item->expiresAfter(86400);
            $json = file_get_contents('https://www.pricemotion.nl/api/pubkeys');
            if ($json === false) {
                throw new RuntimeException('Could not retrieve Pricemotion public keys');
            }
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            if (empty($data['signatures']) || !is_array($data['signatures'])) {
                throw new RuntimeException("Pricemotion public keys response does not contain 'signatures' element");
            }
            return array_values($data['signatures']);
        });
    }
}
