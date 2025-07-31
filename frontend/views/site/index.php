<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Task Tracker';
?>
<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Hello!</h1>
            <p class="fs-5 fw-light">This is my first time using Yii. Click below to try out my task tracker tool.</p>
            <p><a class="btn btn-lg btn-success" href="<?= Url::to(['site/tracker']) ?>">📋 Get started</a></p>
        </div>
    </div>
</div>
