<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class OperationHistory extends \yii\db\ActiveRecord 
{
    const OPERATION_TYPE_TRANSFER       = 'transfer';
    const OPERATION_TYPE_TRANSFER_MINUS = 'transferMinus';
    const OPERATION_TYPE_REFILL         = 'refill';

    public static function tableName()
    {
        return 'operationHistory';
    }

    public function rules()
    {
        return [[
                ['toUserId', 'sum', 'operationType'], 'required'],
                [['sum'],'integer', 'integerOnly' => true,],
                ['toUserId', 'validateToUser'],
                [['sum', 'userId'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'id' => 'ID',
          'sum' => 'Сумма',
          'userId' => 'ID пользователя',
          'toUserId' => 'ID пользователя'
        ];
    }

    public function getToUser()
    {
       return $this->hasOne(User::className(), ['id' => 'toUserId']);
    }

    // проверяем существование пользователя
    public function validateToUser($attribute, $params)
    {
        $user = User::find()->where(['id' => $this->$attribute])->one();

        if (empty($user)){
            $this->addError($attribute, 'User not found');
        }
    }
}