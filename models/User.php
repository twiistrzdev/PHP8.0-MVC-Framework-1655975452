<?php

namespace app\models;

use app\core\DbModel as Model;

/**
 * Class User
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\models
 */
class User extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public int $status = self::STATUS_INACTIVE;
    public string $password = '';
    public string $confirmPassword = '';

    /**
     * User table name.
     *
     * @return string
     */
    public function tableName(): string
    {
        return 'users';
    }

    /**
     * User save.
     *
     * @return boolean
     */
    public function save(): bool
    {
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    /**
     * User rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => self::RULE_REQUIRED,
            'lastname' => self::RULE_REQUIRED,
            'email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [
                    self::RULE_UNIQUE,
                    'class' => self::class,
                ]
            ],
            'password' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_MIN,
                    'min' => 8
                ]
            ],
            'confirmPassword' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_MATCH,
                    'match' => 'password'
                ]
            ],
        ];
    }

    /**
     * User labels
     *
     * @return array
     */
    public function labels(): array
    {
        return [
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email address',
            'password' => 'Password',
            'confirmPassword' => 'Confirm password',
        ];
    }

    /**
     * User attributes.
     *
     * @return array
     */
    public function attributes(): array
    {
        return ['firstname', 'lastname', 'email', 'status', 'password'];
    }
}
