<?php

/** @var yii\web\View $this */
/** @var common\models\Task $task */
/** @var common\models\Task[] $tasks */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Task Tracker';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-tracker">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addTaskModal">
            Add New Task
        </button>
        
        <div
            class="modal fade"
            id="addTaskModal"
            tabindex="-1"
            aria-labelledby="add-task-modal"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="add-task-modal">
                        Add Task
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'task-form',
                        'action' => ['task/create'],
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'validateOnChange' => false,
                        'validateOnBlur' => false,
                    ]); ?>

                    <div class="mb-3">
                        <?= $form->field($task, 'name')->textInput([
                            'placeholder' => 'Enter task name'
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <?= $form->field($task, 'deadline')->input('date') ?>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Close
                    </button>
                        <?= Html::submitButton('Save Task', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tasks)): ?>
                        <tr>
                            <td colspan="4"
                                class="text-center">
                                No tasks yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tasks as $taskItem): ?>
                            <tr>
                                <td><?= Html::encode($taskItem->name) ?></td>
                                <td><?= Html::encode($taskItem->deadline) ?></td>
                                <td>
                                    <span class="badge bg-<?= $taskItem->status == 0 ? 'warning' : 'success' ?>">
                                        <?= $taskItem->status == 0 ? 'Incomplete' : 'Complete' ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
