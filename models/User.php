<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    // сделать перевод
    public function transferSum($sum, $toUser)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {

          if ($this->balance < $sum){
            throw new \Exception('User ID:' . $this->id. ' Has no balance for transaction');
          }

          if ($this->id == $toUser->id){
            throw new \Exception('User cannot be self');
          }

          $operationHistory = new OperationHistory;

          $operationHistory->sum           = $sum;
          $operationHistory->userId        = $this->id;
          $operationHistory->toUserId      = $toUser->id;
          $operationHistory->operationType = OperationHistory::OPERATION_TYPE_TRANSFER;
          $operationHistory->save();

          $operationHistory = new OperationHistory;

          $operationHistory->sum           = -$sum;
          $operationHistory->userId        = 0;
          $operationHistory->toUserId      = $this->id;
          $operationHistory->operationType = OperationHistory::OPERATION_TYPE_TRANSFER_MINUS;
          $operationHistory->save();

          $transaction->commit();

        } catch(Exception $e) {
          $transaction->rollback();
          return array('success' => false, 'error' => 'Error Processing Request ' . $e->getMesssage());
        }

        $this->recalculateBalanсe();
        $toUser->recalculateBalanсe();

        return array('success' => true);
    }

    // пересчет баланса
    public function recalculateBalanсe()
    {
      $result = (new \yii\db\Query())
          ->select(['sum(sum) as sum'])
          ->from('operationHistory')
          ->where(['toUserId' => $this->id])
          ->one();
      if (empty($result['sum'])){
        $this->balance = 0;
      }else{
        $this->balance = $result['sum'];
      }
      
      $this->save();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    public function attributeLabels()
    {
        return [
          'id' => 'ID',
          'name' => 'Имя',
          'balance' => 'Баланс',
          ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
