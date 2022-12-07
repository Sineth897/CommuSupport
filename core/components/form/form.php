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

    public function textArea($model, $label, $attribute,$size = [10,30]): void
    {
        echo sprintf('<div></div><label>%s :</label></div>', $label);
        echo sprintf("<textarea name='%s' rows='%s' cols='%s'>%s</textarea>", $attribute, $size[0], $size[1], $model->{$attribute});
        echo sprintf('<p>%s</p>', $model->getFirstError($attribute));
    }

    public function dropDownList($model, $label, $attribute, $options): void
    {
        echo sprintf('<label>%s :</label>', $label);
        echo sprintf("<select name='%s' >", $attribute);
        echo "<option value=''>Select</option>";
        foreach ($options as $key => $value) {
            $selected = $model->{$attribute} == $key ? 'selected' : '';
            echo sprintf("<option value='%s' %s>%s</option>", $key, $selected, $value);
        }
        echo '</select>';
        echo sprintf('<p>%s</p>', $model->getFirstError($attribute));
    }

    public function submitButton($label) : void
    {
        echo sprintf("<button type='submit'>%s</button>", $label);
    }


}