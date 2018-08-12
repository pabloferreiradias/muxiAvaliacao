<?php

namespace App\Models;

use Validator;
use Log;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * @var Illuminate\Support\MessageBag|null
     */
    protected $errors;

    /**
     * @return array[] Validation rules
     */
    protected function rules()
    {
        return [];
    }

    /**
     * @return array[] Validation messages
     */
    protected function messages()
    {
        return [];
    }

    /**
     * @return Illuminate\Validation\Validator
     */
    protected function getValidator()
    {
        return Validator::make($this->getAttributes(), $this->rules(), $this->messages());
    }

    /**
     * Validates this model data. The errors will be stored in $this->getErrors()
     * @return boolean Success or failure
     */
    public function validate()
    {
        $validator = $this->getValidator();

        if ($validator->fails()) {
            $messages = json_encode($validator->messages(), JSON_UNESCAPED_UNICODE);
            Log::error($messages);
            $this->errors = $validator->messages();
            return false;
        }

        return true;
    }

    /**
     * @return Illuminate\Support\MessageBag|null
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Use validate before save the model
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!$this->validate()) {
            return false;
        }
        return parent::save($options);
    }
}