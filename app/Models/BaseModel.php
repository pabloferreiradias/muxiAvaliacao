<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BaseModel extends Model{

    public $errors;
    
    public $rules;

    public function validate()
    {
        if(empty($this->rules)){
            throw new \Exception('You should set rules for your model validation');
        }
        if(!is_array($this->rules)){
            throw new \Exception('Rules should be an array');
        }
        $v = Validator::make($this->attributes, $this->rules);
        // return the result
        if( $v->fails() ){
            $this->errors = $v->invalid();
            return false;
        }
        return true;
    }

    public function save(array $options = [])
    {
        if( !$this->validate() ){
            return false;
        }
        parent::save($options);
        return true;
    }

    public function saveWithoutValidate(array $options = [])
    {
        parent::save($options);
        return true;
    }    
}
