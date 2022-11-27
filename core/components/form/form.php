<?php

namespace app\core\components\form;

class form
{

    public static function begin($action, $method) : form
    {
        echo sprintf("<form action='%s' method='%s'>", $action, $method);
        return new form();
    }

    public static function end() : void
    {
        echo '</form>';
    }

    public function inputField($model, $label, $type,$attribute): void
    {
        echo sprintf('<label>%s :</label>', $label);
        echo sprintf("<input type='%s' name='%s' value='%s'>",$type ,$attribute, $model->{$attribute});
        echo sprintf('<p>%s</p>', $model->getFirstError($attribute));
    }


}