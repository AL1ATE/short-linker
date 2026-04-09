<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $link_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $referer
 * @property string $visited_at
 *
 * @property Link $link
 */
class LinkVisit extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%link_visits}}';
    }

    public function rules(): array
    {
        return [
            [['link_id', 'visited_at'], 'required'],
            [['link_id'], 'integer'],
            [['user_agent', 'referer'], 'string'],
            [['visited_at'], 'safe'],
            [['ip_address'], 'string', 'max' => 45],
            [
                ['link_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Link::class,
                'targetAttribute' => ['link_id' => 'id'],
            ],
        ];
    }

    public function getLink()
    {
        return $this->hasOne(Link::class, ['id' => 'link_id']);
    }
}