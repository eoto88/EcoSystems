<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_Form {

    public function createForm($data) {
        $attr = '';

        if( isset($data['url']) ) {
            $attr .= ' action="'. $data['url'] .'"';
        } else {
            $attr .= ' action=""';
        }
        if( isset($data['method']) ) {
            $attr .= ' method="'. $data['method'] .'"';
        }
        if( isset($data['api-url']) ) {
            $attr .= ' data-api-url="'. $data['api-url'] .'"';
        }

        $form = '<form '. $attr .' name="'.$data['name'].'" role="form">';

        foreach($data['fields'] as $field) {
            $form .= $this->createField($field);
        }

        $form .= '</form>';

        return $form;
    }

    public function createField($data) {
        $label = '';
        $input = '';
        $value = '';

        if ( isset($data['label']) ) {
            $label = '<label>'. $data['label'] .':</label>';
        }

        if ( ! isset($data['type']) ) {
            $data['type'] = 'text';
        }

        if( isset($data['value']) ) {
            $value = 'value="'.$data['value'].'"';
        }

        switch($data['type']) {
            case 'hidden':
                $input = '<input type="hidden" name="'.$data['name'].'" '.$value.' />';
                break;
            case 'submit':
                $input = '<button type="submit" class="btn btn-default">Submit</button>'; // TODO Label
                break;
            case 'checkbox':
                // TODO checked="checked"
                $input = '<div class="checkbox"><label>';
                $input .= '<input type="checkbox" name="'.$data['name'].'" value="'.$data['value'].'">';
                $input .= $data['label'].'</label></div>';
                break;
            case 'select';
                // TODO selected
                $input = '<select name="'.$data['name'].'" class="form-control">';
                foreach($data['values'] as $key => $type) {
                    $input .= '<option value="'. $key .'">'. $type .'</option>';
                }
                $input .= '</select>';
                break;
            case 'password':
                $input = '<input type="password" id="'.$data['id'].'" name="'.$data['name'].'" class="form-control" />';
                $input .= '<div class="show-password-wrap">
                    <input name="show-password" type="checkbox" id="show-'.$data['id'].'" class="chk-show-password" role="checkbox" aria-checked="false" value="1" />
                    <label for="show-password" title="Show Password">Show</label>
                </div>';
                break;
            // TODO Textarea
            // TODO Numberfield
            case 'text';
            default;
                $attr = '';
                if( isset($data['maxlength']) ) {
                    $attr .= ' maxlength="'. $data['maxlength'] .'"';
                }
                if( isset($data['disabled']) ) {
                    $attr .= ' disabled';
                }
                $input = '<input type="text" name="'.$data['name'].'" '.$value.' class="form-control"'. $attr .' />';
        }

        if( $data['type'] == 'hidden' || $data['type'] == 'submit' || $data['type'] == 'checkbox' ) {
            return $input;
        } else {
            return '<div class="form-group">'.$label.$input.'</div>';
        }
    }
}