<?php

namespace App\Models;

class OrderServer extends BaseModel
{
    protected $connection = 'pgsql_server';
    
    protected $fillable = [
        'id',
        'pos_code',
        'value',
    ];

    protected $table = 'orders';

    /**
     * @return array[] Validation rules
     */
    public function rules()
    {
        return [
            'id' => 'integer',
            'pos_code' => 'required|string',
            'value' => 'required|numeric|between:0,99999999999999999.99',
        ];
    }
}
