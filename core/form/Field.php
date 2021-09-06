<?php

namespace app\core\form;

use app\core\Model;

/**
 * Class Field
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core\form
 */
class Field
{
    public const TYPE_TEXT = 'text';
    public const TYPE_EMAIL = 'email';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'NUMBER';

    public string $type;
    public Model $model;
    public string $attribute;

    /**
     * Field constructor.
     *
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->type = self::TYPE_TEXT;
        $this->attribute = $attribute;
    }

    /**
     * Field toString.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '
            <div>
                <label>%s</label><br>
                <input type="%s" name="%s" value="%s" class="%s">
                <div class="invalid-feedback">%s</div>
            </div>
        ',
            $this->model->getLabel(attribute: $this->attribute),
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError(attribute: $this->attribute) ? ' is-invalid' : '',
            $this->model->getFirstError(attribute: $this->attribute)
        );
    }

    /**
     * Set field type to text.
     *
     * @return Field
     */
    public function textField(): Field
    {
        $this->type = self::TYPE_TEXT;
        return $this;
    }

    /**
     * Set field type to email.
     *
     * @return Field
     */
    public function emailField(): Field
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    /**
     * Set field type to password.
     *
     * @return Field
     */
    public function passwordField(): Field
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    /**
     * Set field type to number.
     *
     * @return Field
     */
    public function numberField(): Field
    {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }
}
