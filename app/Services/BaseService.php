<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

abstract class BaseService
{
    protected array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    protected function validate(array $data, array $rules): bool
    {
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            $this->errors = $validator->errors()->toArray();
            return false
        }
        
        return true;
    }

    protected function getErrors(): array
    {
        return $this->errors;
    }
}
