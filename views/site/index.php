<?php

/** @var yii\web\View $this */
/** @var app\models\forms\CreateShortLinkForm $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Сервис коротких ссылок + QR';
$this->registerJsFile('@web/js/short-link.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="site-index">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-4 text-center"><?= Html::encode($this->title) ?></h1>

                    <div class="mb-3">
                        <label for="url-input" class="form-label">Введите URL</label>
                        <div class="input-group">
                            <input
                                type="text"
                                id="url-input"
                                class="form-control"
                                placeholder="https://example.com"
                            >
                            <button
                                type="button"
                                id="create-short-link-btn"
                                class="btn btn-primary"
                                data-url="<?= Url::to(['site/create-short-url']) ?>"
                            >
                                OK
                            </button>
                        </div>
                    </div>

                    <div id="message-box" class="alert d-none"></div>

                    <div id="result-box" class="d-none mt-4">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h2 class="h5 mb-3">Результат</h2>

                                <p class="mb-2">
                                    <strong>Короткая ссылка:</strong><br>
                                    <a href="#" target="_blank" id="short-url-link"></a>
                                </p>

                                <div class="mt-3">
                                    <strong>QR-код:</strong><br>
                                    <img
                                        id="qr-code-image"
                                        src=""
                                        alt="QR Code"
                                        class="img-fluid mt-2 border rounded"
                                        style="max-width: 250px;"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>