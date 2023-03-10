<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomValidator
{
    public Request $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validate(array $arr)
    {
        $validator = Validator::make($this->request->all(), $arr);
        if ($validator->messages()->all()) {
            throw new HttpException(422, 'Ошибка валидации: ' . implode(', ', $validator->messages()->all()));
        } else {
            return $validator->getData();
        }
    }
}
