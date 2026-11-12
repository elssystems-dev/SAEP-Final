<?php
declare(strict_types=1);

namespace VetVida\Security;

final class CryptoService {
    private const CIPHER_METHOD = 'aes-256-cbc';

    private static function deriveKey(): string {
        $configFile = dirname(__DIR__, 2) . '/config/database.ini';
        $config = file_exists($configFile) ? parse_ini_file($configFile, true) : [];
        $appKey = (string)($config['security']['app_key'] ?? 'vetvida-saep-secret-2026');
        return hash('sha256', $appKey);
    }

    public static function encrypt(string $plainText): string {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt(trim($plainText), self::CIPHER_METHOD, self::deriveKey(), OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt(string $cipherText): string {
        $raw = base64_decode($cipherText, true);
        if ($raw === false || strlen($raw) < 17) {
            return $cipherText;
        }

        $iv = substr($raw, 0, 16);
        $data = substr($raw, 16);
        $decrypted = openssl_decrypt($data, self::CIPHER_METHOD, self::deriveKey(), OPENSSL_RAW_DATA, $iv);
        return $decrypted !== false ? $decrypted : '***.***.***-**';
    }

    public static function mask(string $document): string {
        $digits = preg_replace('/\D/', '', $document);
        if (strlen($digits) === 11) {
            return substr($digits, 0, 3) . '.***.***-' . substr($digits, -2);
        }
        return $document;
    }
}
