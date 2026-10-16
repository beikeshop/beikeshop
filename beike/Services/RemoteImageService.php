<?php

namespace Beike\Services;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use RuntimeException;

class RemoteImageService
{
    private const MAX_BYTES = 10 * 1024 * 1024;

    public function download(string $url): string
    {
        $body       = $this->fetch($url);
        $info       = @getimagesizefromstring($body);
        $extensions = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'];

        if (! $info || ! isset($extensions[$info[2]]) || $info[0] * $info[1] > 16000000) {
            throw new RuntimeException('Invalid or oversized image.');
        }

        // Decode and re-encode: never persist the remote filename or untrusted bytes.
        $extension = $extensions[$info[2]];
        $image     = (new ImageManager(['driver' => 'gd']))->make($body)->encode($extension);
        if (strlen((string) $image) > self::MAX_BYTES) {
            throw new RuntimeException('Encoded image is too large.');
        }

        $directory = public_path('upload/image');
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException('Cannot create image directory.');
        }
        $path = 'upload/image/' . Str::random(40) . '.' . $extension;
        if (file_put_contents(public_path($path), (string) $image, LOCK_EX) === false) {
            throw new RuntimeException('Cannot save image.');
        }

        return $path;
    }

    private function fetch(string $url): string
    {
        $parts = parse_url($url);
        if (! $parts || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)
                     || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])) {
            throw new RuntimeException('Invalid image URL.');
        }

        $scheme = strtolower($parts['scheme']);
        $port   = $parts['port'] ?? ($scheme === 'https' ? 443 : 80);
        if ($port !== ($scheme === 'https' ? 443 : 80)) {
            throw new RuntimeException('Image URL port is not allowed.');
        }

        $host        = strtolower(trim($parts['host'], '[]'));
        $addresses   = $this->publicAddresses($host);
        $body        = '';
        $received    = 0;
        $headerBytes = 0;
        $curl        = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_PROXY          => '',
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER     => ['Accept: image/jpeg,image/png,image/gif,image/webp'],
            CURLOPT_USERAGENT      => 'BeikeShop Image Fetcher',
            CURLOPT_HEADERFUNCTION => static function ($handle, string $header) use (&$headerBytes): int {
                $headerBytes += strlen($header);
                if ($headerBytes > 65536 || (stripos($header, 'Content-Length:') === 0
                    && (float) trim(substr($header, 15)) > self::MAX_BYTES)) {
                    return 0;
                }

                return strlen($header);
            },
            CURLOPT_WRITEFUNCTION => static function ($handle, string $chunk) use (&$body, &$received): int {
                $received += strlen($chunk);
                if ($received > self::MAX_BYTES) {
                    return 0;
                }
                $body .= $chunk;

                return strlen($chunk);
            },
        ]);
        if (! filter_var($host, FILTER_VALIDATE_IP)) {
            $address = str_contains($addresses[0], ':') ? '[' . $addresses[0] . ']' : $addresses[0];
            // Pin the validated DNS result to prevent DNS rebinding.
            curl_setopt($curl, CURLOPT_RESOLVE, ["{$host}:{$port}:{$address}"]);
        }

        try {
            $success = curl_exec($curl);
            $status  = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            if ($success === false || $status !== 200) {
                throw new RuntimeException(sprintf(
                    'Image download failed (HTTP %d, cURL %d): %s',
                    $status,
                    curl_errno($curl),
                    curl_error($curl)
                ));
            }
        } finally {
            curl_close($curl);
        }

        return $body;
    }

    private function publicAddresses(string $host): array
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $addresses = [$host];
        } else {
            if (! preg_match('/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/i', $host)) {
                throw new RuntimeException('Invalid image hostname.');
            }
            $records   = dns_get_record($host, DNS_A | DNS_AAAA);
            $addresses = [];
            foreach ($records ?: [] as $record) {
                if (isset($record['ip']) || isset($record['ipv6'])) {
                    $addresses[] = $record['ip'] ?? $record['ipv6'];
                }
            }
        }

        if (! $addresses) {
            throw new RuntimeException('Image hostname could not be resolved.');
        }
        foreach ($addresses as $address) {
            $packed    = inet_pton($address);
            $isUnicast = $packed !== false && (strlen($packed) === 4
                ? ord($packed[0]) < 224
                : (ord($packed[0]) & 0xE0) === 0x20);
            if (! $isUnicast || ! filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_GLOBAL_RANGE)) {
                throw new RuntimeException('Private or reserved image addresses are not allowed.');
            }
        }

        return $addresses;
    }
}
