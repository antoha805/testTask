<?php
class Poster extends Eloquent{
    protected $guarded = array(
        'id',
        'created_at',
        'updated_at',
    );

    public static function getValidationRules() {
        $validation = array(
            'name'     => 'required|min:1|max:200',
            'body'      => 'required',
        );
        return $validation;
    }
}