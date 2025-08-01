<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\data\ActiveDataProvider;
use common\models\Task;

/**
 * Task controller
 */
class TaskController extends Controller
{
    private const PAGE_SIZE = 10;
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'update', 'delete', 'tracker', 'toggle'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'tracker', 'toggle'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'only' => ['api-tasks'],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'update' => ['post'],
                    'delete' => ['post'],
                    'toggle' => ['post'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => Task::find()->where(['user_id' => Yii::$app->user->id]),
            'pagination' => [
                'pageSize' => self::PAGE_SIZE,
            ],
        ]);

        return $this->render('@frontend/views/site/tracker', [
            'task' => $task,
            'dataProvider' => $dataProvider,
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

    /**
     * Updates an existing task.
     *
     * @return mixed
     */
    public function actionUpdate()
    {
        $taskId = Yii::$app->request->post('id');
        
        if ($taskId) {
            $task = $this->findUserTask($taskId);
            
            if (!$task) {
                Yii::$app->session->setFlash('error', 'Task not found');
                return $this->redirect(['task/tracker']);
            }
            
            $successMessage = 'Task updated successfully!';
            
            if ($task->load(Yii::$app->request->post()) && $task->save()) {
                Yii::$app->session->setFlash('success', $successMessage);
                return $this->redirect(['task/tracker']);
            }
        } else {
            Yii::$app->session->setFlash('error', 'No task ID provided for update');
        }

        return $this->redirect(['task/tracker']);
    }

    /**
     * Toggles task completion status.
     *
     * @param integer $id Task ID
     * @return mixed
     */
    public function actionToggle($id)
    {
        $task = $this->findUserTask($id);
        
        if (!$task) {
            Yii::$app->session->setFlash('error', 'Task not found');
            return $this->redirect(['task/tracker']);
        }

        $task->status = $task->status == Task::STATUS_COMPLETE ? Task::STATUS_INCOMPLETE : Task::STATUS_COMPLETE;
        
        if ($task->save()) {
            $statusText = $task->status == Task::STATUS_COMPLETE ? 'completed' : 'marked as incomplete';
            Yii::$app->session->setFlash('success', "Task {$statusText} successfully!");
        } else {
            Yii::$app->session->setFlash('error', 'Failed to update task status');
        }

        return $this->redirect(['task/tracker']);
    }

    /**
     * Deletes a task.
     *
     * @param integer $id Task ID
     * @return mixed
     */
    public function actionDelete($id)
    {
        $task = $this->findUserTask($id);
        
        if (!$task) {
            Yii::$app->session->setFlash('error', 'Task not found');
            return $this->redirect(['task/tracker']);
        }

        if ($task->delete()) {
            Yii::$app->session->setFlash('success', 'Task deleted successfully!');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete task');
        }

        return $this->redirect(['task/tracker']);
    }

    /**
     * Returns all tasks for the current user as JSON.
     * 
     * @return Response
     */
    public function actionApiTasks()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $tasks = Task::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->asArray()
            ->all();

        return [
            'success' => true,
            'tasks' => $tasks,
        ];
    }

    /**
     * Find task by user ID.
     * 
     * @param integer $id Task ID
     * @return Task|null
     */
    private function findUserTask($id)
    {
        return Task::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id
        ]);
    }
}
