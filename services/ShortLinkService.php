<?php

namespace app\services;

use app\models\forms\CreateShortLinkForm;
use app\models\Link;
use Yii;

class ShortLinkService
{
    private const SHORT_CODE_LENGTH = 6;
    private const MAX_GENERATION_ATTEMPTS = 20;
    private const SHORT_CODE_ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public function __construct(
        private readonly UrlAvailabilityService $urlAvailabilityService,
        private readonly QrCodeService $qrCodeService
    ) {}

    public function createFromForm(CreateShortLinkForm $form): array
    {
        if (!$form->validate()) {
            return $this->errorResponse(
                $form->getFirstError('url') ?: 'Ошибка валидации.'
            );
        }

        if (!$this->urlAvailabilityService->isAvailable($form->url)) {
            return $this->errorResponse('Данный URL не доступен');
        }

        $link = $this->findOrCreateLink($form->url);
        $shortUrl = $this->buildShortUrl($link->short_code);

        return $this->successResponse($link, $shortUrl);
    }

    private function findOrCreateLink(string $url): Link
    {
        $link = Link::find()->where(['original_url' => $url])->one();

        if ($link !== null) {
            return $link;
        }

        $link = new Link();
        $link->original_url = $url;
        $link->short_code = $this->generateUniqueShortCode();
        $link->click_count = 0;
        $link->created_at = date('Y-m-d H:i:s');
        $link->updated_at = date('Y-m-d H:i:s');

        if (!$link->save()) {
            throw new \RuntimeException('Не удалось сохранить ссылку.');
        }

        return $link;
    }

    private function generateUniqueShortCode(): string
    {
        for ($attempt = 0; $attempt < self::MAX_GENERATION_ATTEMPTS; $attempt++) {
            $code = $this->generateRandomCode();

            $exists = Link::find()->where(['short_code' => $code])->exists();

            if (!$exists) {
                return $code;
            }
        }

        throw new \RuntimeException('Не удалось сгенерировать уникальный короткий код.');
    }

    private function generateRandomCode(): string
    {
        $alphabetLength = strlen(self::SHORT_CODE_ALPHABET);
        $code = '';

        for ($i = 0; $i < self::SHORT_CODE_LENGTH; $i++) {
            $code .= self::SHORT_CODE_ALPHABET[random_int(0, $alphabetLength - 1)];
        }

        return $code;
    }

    private function buildShortUrl(string $shortCode): string
    {
        $baseUrl = rtrim($_ENV['BASE_URL'] ?? Yii::$app->request->hostInfo, '/');

        return $baseUrl . '/' . $shortCode;
    }

    private function errorResponse(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }

    private function successResponse(Link $link, string $shortUrl): array
    {
        return [
            'success' => true,
            'message' => 'Короткая ссылка успешно создана.',
            'data' => [
                'original_url' => $link->original_url,
                'short_code' => $link->short_code,
                'short_url' => $shortUrl,
                'qr_code' => $this->qrCodeService->generateBase64($shortUrl),
            ],
        ];
    }
}