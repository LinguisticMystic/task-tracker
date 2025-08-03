<?php

use common\models\Task;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */
/** @var Task $task */
/** @var Task[] $tasks */

$this->title = 'Task Tracker';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-tracker">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
        <button
            type="button"
            class="btn btn-primary mb-3"
            data-bs-toggle="modal"
            data-bs-target="#addTaskModal"
            onclick="resetModalForm()">
            Add New Task
        </button>

        <div class="modal fade"
            id="addTaskModal">
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
                            data-bs-dismiss="modal"></button>
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
                        <input type="hidden"
                            id="task-id"
                            name="id"
                            value="">
                        <div class="mb-3">
                            <?= $form->field($task, 'name')->textInput([
                                'placeholder' => 'Enter task name',
                                'id' => 'task-name'
                            ]) ?>
                        </div>
                        <div class="mb-3">
                            <?= $form->field($task, 'deadline')->input('date', [
                                'id' => 'task-deadline'
                            ]) ?>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                Close
                            </button>
                            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'id' => 'save-button']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-responsive mt-3'],
            'rowOptions' => function ($model) {
                return ['class' => $model->status == Task::STATUS_COMPLETE ? 'bg-success' : ''];
            },
            'columns' => [
                [
                    'attribute' => 'name',
                    'contentOptions' => ['class' => 'your-custom-class'],
                    'enableSorting' => true,
                ],
                [
                    'attribute' => 'deadline',
                    'format' => 'date',
                    'enableSorting' => true,
                    'contentOptions' => function ($model) {
                        return ['class' => $model->isDeadlineMissed() ? 'text-danger fw-bold' : ''];
                    },
                    'value' => function ($model) {
                        $html = Html::encode($model->deadline);
                        if ($model->isDeadlineMissed()) {
                            $html .= ' <span class="badge bg-danger">Missed Deadline</span>';
                        }
                        return $html;
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'enableSorting' => true,
                    'value' => function ($model) {
                        $class = $model->status == Task::STATUS_INCOMPLETE ? 'warning' : 'success';
                        $text = $model->status == Task::STATUS_INCOMPLETE ? 'Incomplete' : 'Complete';
                        return "<span class='badge bg-{$class}'>{$text}</span>";
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{edit} {delete} {toggle}',
                    'buttons' => [
                        'edit' => function ($url, $model) {
                            return Html::button('Edit', [
                                'class' => 'btn btn-sm btn-primary',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#addTaskModal',
                                'data-task-id' => $model->id,
                                'data-task-name' => Html::encode($model->name),
                                'data-task-deadline' => $model->deadline,
                                'onclick' => 'editTask(this)',
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('Delete', ['task/delete', 'id' => $model->id], [
                                'class' => 'btn btn-sm btn-danger',
                                'data-method' => 'post',
                                'data-confirm' => 'Are you sure you want to delete this task?',
                            ]);
                        },
                        'toggle' => function ($url, $model) {
                            $isComplete = $model->status == Task::STATUS_COMPLETE;
                            return Html::a(
                                $isComplete ? 'Restore Task' : 'Complete Task',
                                ['task/toggle', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-sm ' . ($isComplete ? 'btn-warning' : 'btn-success'),
                                    'data-method' => 'post',
                                    'data-confirm' => $isComplete
                                        ? 'Are you sure you want to restore this task?'
                                        : 'Are you sure you want to complete this task?',
                                ]
                            );
                        },
                    ]
                ]
            ],
        ]);
        ?>
    </div>
</div>

<script>
    function editTask(button) {
        const taskId = button.getAttribute('data-task-id');
        const taskName = button.getAttribute('data-task-name');
        const taskDeadline = button.getAttribute('data-task-deadline');

        document.getElementById('task-id').value = taskId;
        document.getElementById('task-name').value = taskName;
        document.getElementById('task-deadline').value = taskDeadline;

        document.getElementById('task-form').action = '<?= Url::to(['task/update']) ?>';
    }

    function resetModalForm() {
        document.getElementById('task-id').value = '';
        document.getElementById('task-name').value = '';
        document.getElementById('task-deadline').value = '';

        document.getElementById('task-form').action = '<?= Url::to(['task/create']) ?>';
    }
</script>
