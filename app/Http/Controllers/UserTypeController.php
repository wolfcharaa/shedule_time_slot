<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use App\Service\CustomValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserTypeController extends Controller
{
    public function create(Request $request, CustomValidator $validator)
    {
        $requestData = $validator->validate(
            ["name" => "required"]
        );
        $userType = new UserType();
        $userType->name = $requestData["name"];
        $userType->save();
        return new JsonResponse([
            'message' => 'Добавлен новый тип'
        ]);

    }

    public function delete(Request $request, int $id)
    {
        $userType = UserType::query()->find($id);

    }
}
