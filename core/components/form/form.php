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

    public function inputField($model, $label, $type, $attribute, $id = ""): void
    {
        echo sprintf('<label>%s :</label>', $label);
        if($id == "") {
            echo sprintf('<input type="%s" name="%s" value="%s">', $type, $attribute, $model->{$attribute});
        } else {
            echo sprintf('<input type="%s" name="%s" value="%s" id="%s">', $type, $attribute, $model->{$attribute}, $id);
        }
        echo sprintf('<p>%s</p>', $model->getFirstError($attribute));
    }

    public function textArea($model, $label, $attribute,$size = [10,30]): void
    {
        echo sprintf('<div></div><label>%s :</label></div>', $label);
        echo sprintf("<textarea name='%s' rows='%s' cols='%s'>%s</textarea>", $attribute, $size[0], $size[1], $model->{$attribute});
        echo sprintf('<p>%s</p>', $model->getFirstError($attribute));
    }

    public function dropDownList($model, $label, $attribute, $options, $id =""): void
    {
        echo sprintf('<label>%s :</label>', $label);
        if($id == ""){
            echo sprintf("<select name='%s'>", $attribute);
        }else{

            echo sprintf("<select name='%s' id='%s'>", $attribute, $id);
        }
        echo "<option value=''>Select</option>";
        foreach ($options as $key => $value) {
            if($attribute){
                $selected = $model->{$attribute} == $key ? 'selected' : '';
            }else{
                $selected = '';
            }
            echo sprintf("<option value='%s' %s>%s</option>", $key, $selected, $value);
        }
        echo '</select>';
        echo sprintf('<p>%s</p>', $model->getFirstError($attribute));
    }

    public function button($label, $type = 'submit', $id = '') : void
    {
        if($id == ""){
            echo sprintf("<button type='%s'>%s</button>", $type, $label);
        }else{
            echo sprintf("<button type='%s' id='%s'>%s</button>", $type, $id, $label);
        }
    }


}