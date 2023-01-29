<?php

namespace app\core\components\form;

class form
{

    public static function begin($action, $method,$cols =1) : form
    {
        $colClass = $cols == 1 ? "form-grid-1" : "form-grid-2-2";
        echo sprintf("<form action='%s' method='%s' class='%s'>", $action, $method, $colClass);
        return new form();
    }

    public static function end() : void
    {
        echo '</form>';
    }

    public function inputField($model, $label, $type, $attribute, $id = ""): void
    {
        echo "<div class='form-group'>";
        echo sprintf('<label class="form-label">%s :</label>', $label);
        if($id == "") {
            echo sprintf('<input type="%s" name="%s" value="%s" class="basic-input-field">', $type, $attribute, $model->{$attribute});
        } else {
            echo sprintf('<input type="%s" name="%s" value="%s" id="%s">', $type, $attribute, $model->{$attribute}, $id);
        }
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($attribute));
        echo "</div>";
    }

    public function textArea($model, $label, $attribute,$size = [10,30]): void
    {
        echo "<div class='form-group'>";
        echo sprintf('<div></div><label class="form-label">%s :</label></div>', $label);
        echo sprintf("<textarea name='%s' rows='%s' cols='%s' class='basic-input-field'>%s</textarea>", $attribute, $size[0], $size[1], $model->{$attribute});
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($attribute));
        echo "<div>";
    }

    public function dropDownList($model, $label, $attribute, $options, $id =""): void
    {
        echo "<div class='form-group'>";
        echo sprintf('<label class="form-label">%s :</label>', $label);
        if($id == ""){
            echo sprintf("<select name='%s' class='basic-input-field'>", $attribute);
        }else{

            echo sprintf("<select name='%s' id='%s' class='basic-input-field'>", $attribute, $id);
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
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($attribute));
        echo "</div>";
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