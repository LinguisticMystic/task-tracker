<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Task;

/**
 * Task controller
 */
class TaskController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'update', 'delete', 'tracker'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'tracker'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'update' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays task tracker page.
     *
     * @return mixed
     */
    public function actionTracker()
    {
        $task = new Task();

        $tasks = Task::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        
        return $this->render('@frontend/views/site/tracker', [
            'task' => $task,
            'tasks' => $tasks,
        ]);
    }

    /**
     * Creates a new task.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $task = new Task();
        $task->user_id = Yii::$app->user->id;
        
        if ($task->load(Yii::$app->request->post()) && $task->save()) {
            Yii::$app->session->setFlash('success', 'Task created successfully!');
            return $this->redirect(['task/tracker']);
        }

        return $this->redirect(['task/tracker']);
    }
}
