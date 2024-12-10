<?php

namespace App\Libraries;

class FormBuilder
{
    protected $form;

    public function __construct()
    {
        $this->form = '';
    }

    public function open($action = '', $method = 'post', $attributes = [])
    {
        $attr = $this->parseAttributes($attributes);
        $this->form .= "<form action='{$action}' method='{$method}' {$attr}>";
        return $this;
    }

    public function close()
    {
        $this->form .= '</form>';
        return $this;
    }

    public function input($type, $name, $value = '', $attributes = [])
    {
        $attr = $this->parseAttributes($attributes);
        $_class = '';
        switch ($type) {

            case 'radio':
            case 'checkbox':
                $_class = 'form-check-input';
                # code...
                break;

            default:
                $_class = 'form-control form-control-sm';
                break;
        }
        $this->form .= "<input class='{$_class}' type='{$type}' name='{$name}' value='{$value}' {$attr} />";
        return $this;
    }

    public function textarea($name, $value = '', $attributes = [])
    {
        $attr = $this->parseAttributes($attributes);
        $this->form .= "<textarea  class='form-control form-control-sm' name='{$name}' {$attr}>{$value}</textarea>";
        return $this;
    }

    public function select($name, $options = [], $selected = '', $attributes = [])
    {
        $attr = $this->parseAttributes($attributes);
        $this->form .= "<select class='form-control form-control-sm' name='{$name}' {$attr}>";
        foreach ($options as $key => $value) {
            $isSelected = $selected == $key ? 'selected' : '';
            $this->form .= "<option value='{$key}' {$isSelected}>{$value}</option>";
        }
        $this->form .= '</select>';
        return $this;
    }

    public function label($for, $text, $attributes = [])
    {
        $attr = $this->parseAttributes($attributes);
        $this->form .= "<label for='{$for}' {$attr}>{$text}</label>";
        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }

    protected function parseAttributes($attributes)
    {
        $attr = '';
        foreach ($attributes as $key => $value) {
            $attr .= "{$key}='{$value}' ";
        }
        return $attr;
    }
}
