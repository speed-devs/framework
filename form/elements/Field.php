<?php

namespace speedweb\core\form\elements;

use speedweb\core\form\BaseField;
use speedweb\core\model\Model;

class Field extends BaseField
{

    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_FILE = 'file';
    const TYPE_EMAIL = 'email';

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function renderInput()
    {
        return sprintf('<input type="%s" class="form-control %s" name="%s" value="%s">',
        $this->type,
        $this->model->hasError($this->attribute) ? 'is-invalid' : '',
        $this->attribute,
        $this->model->{$this->attribute});
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function typeField()
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }

    public function typeEmail()
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }
}