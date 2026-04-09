<?php

namespace app\services;

use app\models\Link;
use app\models\LinkVisit;
use yii\web\Request;

class VisitService
{
    public function logVisit(Link $link, Request $request): void
    {
        $visit = new LinkVisit();
        $visit->link_id = $link->id;
        $visit->ip_address = $request->userIP;
        $visit->user_agent = $request->userAgent;
        $visit->referer = $request->referrer;
        $visit->visited_at = date('Y-m-d H:i:s');
        $visit->save(false);
    }

    public function incrementClickCount(Link $link): void
    {
        $link->incrementClickCount();
    }
}