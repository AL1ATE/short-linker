<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $original_url
 * @property string $short_code
 * @property int $click_count
 * @property string $created_at
 * @property string $updated_at
 *
 * @property LinkVisit[] $visits
 */
class Link extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%links}}';
    }

    public function rules(): array
    {
        return [
            [['original_url', 'short_code', 'created_at', 'updated_at'], 'required'],
            [['original_url'], 'string'],
            [['click_count'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['short_code'], 'string', 'max' => 32],
            [['short_code'], 'unique'],
        ];
    }

    public function getVisits()
    {
        return $this->hasMany(LinkVisit::class, ['link_id' => 'id']);
    }

    public function incrementClickCount(): void
    {
        $this->updateCounters(['click_count' => 1]);
    }
}