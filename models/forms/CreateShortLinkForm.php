<?php

namespace app\models\forms;

use yii\base\Model;

class CreateShortLinkForm extends Model
{
    public string $url = '';

    public function rules(): array
    {
        return [
            [['url'], 'required'],
            [['url'], 'trim'],
            [['url'], 'string', 'max' => 2048],
            [['url'], 'validateUrl'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'url' => 'URL',
        ];
    }

    public function validateUrl(string $attribute): void
    {
        $value = $this->$attribute;

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($attribute, 'Введите корректный URL.');
            return;
        }

        $parts = parse_url($value);
        $scheme = strtolower($parts['scheme'] ?? '');

        if (!in_array($scheme, ['http', 'https'], true)) {
            $this->addError($attribute, 'Разрешены только URL с http или https.');
            return;
        }

        if (empty($parts['host'])) {
            $this->addError($attribute, 'У URL должен быть указан host.');
        }
    }
}