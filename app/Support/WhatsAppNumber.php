<?php

namespace App\Support;

class WhatsAppNumber
{
    public function normalize(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (preg_match('~^https?://(?:www\.)?wa\.me/([^/?#]+)~i', $value, $matches)) {
            $value = $matches[1];
        } elseif (preg_match('~^https?://(?:web\.|api\.)?whatsapp\.com/send\?[^#]*phone=([^&#]+)~i', $value, $matches)) {
            $value = urldecode($matches[1]);
        }

        if (! preg_match('/^\+?[0-9\s()\-]+$/', $value)) {
            return $value;
        }

        $digits = preg_replace('/\D+/', '', $value);

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        return $digits;
    }

    public function toLocalInput(?string $value): ?string
    {
        $normalized = $this->normalize($value);

        if ($normalized && preg_match('/^62(8\d+)$/', $normalized, $matches)) {
            return '0'.$matches[1];
        }

        return $normalized;
    }

    public function toUrl(?string $value): ?string
    {
        $normalized = $this->normalize($value);

        if (! $normalized || ! preg_match('/^628\d{7,12}$/', $normalized)) {
            return null;
        }

        return 'https://wa.me/'.$normalized;
    }
}
