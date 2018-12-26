<?php

namespace Wearesho\Deployer\Models;

use Horat1us\Yii\Exceptions\ModelException;
use yii\behaviors\AttributesBehavior;
use yii\db;
use yii\base;
use yii\web;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package Wearesho\Deployer\Models
 *
 * @property string $id [integer]
 * @property string $login [varchar(255)]
 * @property string $password_hash [varchar(255)]
 * @property string $authenticator_secret
 * @property string $auth_key
 *
 * @property-write string $password
 */
class User extends db\ActiveRecord implements web\IdentityInterface
{
    public static function tableName(): string
    {
        return 'user';
    }

    public function behaviors(): array
    {
        return [
            'attributes' => [
                'class' => AttributesBehavior::class,
                'attributes' => [
                    'auth_key' => [
                        db\ActiveRecord::EVENT_BEFORE_INSERT => function () {
                            return \Yii::$app->security->generateRandomString();
                        },
                    ],
                ],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['login', 'password_hash', 'authenticator_secret',], 'required',],
            [['login',], 'unique',],
        ];
    }

    public static function findIdentity($id): ?User
    {
        return static::findOne((int)$id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $authKey === $this->auth_key;
    }

    /**
     * @param string $password
     * @throws \Horat1us\Yii\Interfaces\ModelExceptionInterface
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
        if (!$this->isNewRecord) {
            ModelException::saveOrThrow($this);
        }
    }

    public function validatePassword(string $password): bool
    {
        if (empty($this->password_hash)) {
            throw new \BadMethodCallException("Password is not set");
        }
        return password_verify($password, $this->password_hash);
    }
}
