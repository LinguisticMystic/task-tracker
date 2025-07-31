<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Task Tracker';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-tracker">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0">Your Tasks</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">No tasks yet. Add your first task to get started!</p>
                    <button class="btn btn-primary">Add New Task</button>
                </div>
            </div>
        </div>
    </div>
</div>
