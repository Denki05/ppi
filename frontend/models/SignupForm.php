<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    const SCENARIO_UPDATE_USER = 'updateuser';
    
    public $username;
    public $password;
    public $roles;
    public $old_password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Username ini sudah ada.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required', 'except' => self::SCENARIO_UPDATE_USER],
            ['password', 'string', 'min' => 6],
            
            ['roles', 'required'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'roles' => 'Peran',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
