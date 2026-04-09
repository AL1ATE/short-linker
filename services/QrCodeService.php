<?php

namespace app\services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    private const DATA_URI_PREFIX = 'data:image/png;base64,';

    public function generateBase64(string $text, int $size = 250): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($text)
            ->size($size)
            ->margin(10)
            ->build();

        return self::DATA_URI_PREFIX . base64_encode($result->getString());
    }
}