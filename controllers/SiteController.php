<?php

namespace app\controllers;

use app\models\forms\CreateShortLinkForm;
use app\models\Link;
use app\services\ShortLinkService;
use app\services\VisitService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly ShortLinkService $shortLinkService,
        private readonly VisitService $visitService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create-short-url' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'model' => new CreateShortLinkForm(),
        ]);
    }

    public function actionCreateShortUrl(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new CreateShortLinkForm();
        $form->load(Yii::$app->request->post(), '');

        return $this->shortLinkService->createFromForm($form);
    }

    public function actionGo(string $code)
    {
        $link = Link::find()->where(['short_code' => $code])->one();

        if ($link === null) {
            throw new NotFoundHttpException('Короткая ссылка не найдена.');
        }

        $this->visitService->logVisit($link, Yii::$app->request);
        $this->visitService->incrementClickCount($link);

        return $this->redirect($link->original_url);
    }
}