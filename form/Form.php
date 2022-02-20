<?php

namespace speedweb\core\form;

use speedweb\core\Application;
use speedweb\core\form\elements\Field;
use speedweb\core\model\Model;

class Form
{

    public static function begin($action, $method, $options = [])
    {
        $attributes = [];
        foreach ($options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }

        echo sprintf('<form action="%s" method="%s" %s><input type="hidden" name="%s" value="%s">', $action, $method, implode(" ", $attributes), Application::CSRF_SESSION_NAMESPACE, csrf_token());
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute)
    {
        return new Field($model, $attribute);
    }

    public function submitButton(string $title, $options = [])
    {
        $attributes = [];
        foreach ($options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }

        echo sprintf('<button type="submit" %s>%s</button>', implode(" ", $attributes), $title);
    }
}