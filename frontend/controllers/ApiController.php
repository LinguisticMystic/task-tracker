<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\auth\HttpBearerAuth;
use common\models\User;

/**
 * API controller for authentication and other API operations
 */
class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'except' => ['login'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['login', 'tasks'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * API login endpoint - returns access token for username/password.
     * 
     * @return Response
     */
    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        
        if (!$username || !$password) {
            return [
                'success' => false,
                'message' => 'Username and password are required',
            ];
        }
        
        $user = User::findByUsername($username);
        
        if (!$user || !$user->validatePassword($password)) {
            return [
                'success' => false,
                'message' => 'Invalid username or password',
            ];
        }
        
        if (!$user->access_token) {
            $user->generateAccessToken();
            $user->save(false);
        }
        
        return [
            'success' => true,
            'access_token' => $user->access_token,
            'message' => 'Login successful',
        ];
    }

    /**
     * Get current user info (requires authentication).
     * 
     * @return Response
     */
    public function actionMe()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $user = Yii::$app->user->identity;
        
        return [
            'success' => true,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
        ];
    }
} 