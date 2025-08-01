<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Task model
 */
class Task extends ActiveRecord
{
    const STATUS_INCOMPLETE = 0;
    const STATUS_COMPLETE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tasks}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'deadline', 'user_id'], 'required'],
            [['deadline'], 'safe'],
            [['user_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => self::STATUS_INCOMPLETE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'deadline' => 'Deadline',
            'status' => 'Status',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Check if deadline was missed.
     * 
     * @return bool
     */
    public function isDeadlineMissed()
    {
        return strtotime($this->deadline) < strtotime(date('Y-m-d')) && $this->status == self::STATUS_INCOMPLETE;
    }
}
