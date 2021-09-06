<?php

namespace app\core;

/**
 * Abstract Class Model
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    /**
     * Model load datas.
     *
     * @param array $data
     */
    public function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Model rules.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Model labels.
     *
     * @return array
     */
    public function labels(): array
    {
        return [];
    }

    /**
     * Model label getter.
     *
     * @param string $attribute
     * @return string
     */
    public function getLabel(string $attribute): string
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public array $errors = [];

    /**
     * Model validate.
     *
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};

            if (!is_array($rules)) {
                $rules = [$rules];
            }

            foreach ($rules as $rule) {
                $ruleName = $rule;

                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }

                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError(
                        attribute: $attribute,
                        rule: self::RULE_REQUIRED
                    );
                }

                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError(
                        attribute: $attribute,
                        rule: self::RULE_EMAIL
                    );
                }

                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError(
                        attribute: $attribute,
                        rule: self::RULE_MIN,
                        params: $rule
                    );
                }

                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError(
                        attribute: $attribute,
                        rule: self::RULE_MAX,
                        params: $rule
                    );
                }

                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);

                    $this->addError(
                        attribute: $attribute,
                        rule: self::RULE_MATCH,
                        params: $rule
                    );
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $attribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $result = $statement->fetchObject();

                    if ($result) {
                        $this->addError(
                            attribute: $attribute,
                            rule: self::RULE_UNIQUE,
                            params: [
                                'field' => $this->getLabel(attribute: $attribute)
                            ]
                        );
                    }
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Model add error message.
     *
     * @param string $attribute
     * @param string $rule
     * @param array $params
     */
    public function addError(string $attribute, string $rule, array $params = [])
    {
        $message = $this->getRuleMessage()[$rule] ?? '';

        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        $this->errors[$attribute][] = $message;
    }

    /**
     * Model rule message getter.
     *
     * @return array
     */
    public function getRuleMessage(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL    => 'This field must be valid email address',
            self::RULE_MIN      => 'Min length of this field must be {min}',
            self::RULE_MAX      => 'Max length of this field must be {max}',
            self::RULE_MATCH    => 'This field must be the same as {match}',
            self::RULE_UNIQUE   => '{field} already exists',
        ];
    }

    /**
     * Model error checker per attribute.
     *
     * @param string $attribute
     * @return array|false
     */
    public function hasError(string $attribute): array|false
    {
        return $this->errors[$attribute] ?? false;
    }

    /**
     * Model first error getter.
     *
     * @param string $attribute
     * @return string|false
     */
    public function getFirstError(string $attribute): string|false
    {
        return $this->errors[$attribute][0] ?? false;
    }
}
