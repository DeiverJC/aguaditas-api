<?php

namespace App\Restify;

use App\Models\User;
use Binaryk\LaravelRestify\Fields\Field;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;

class UserRepository extends Repository
{
    public static string $model = User::class;

    public static string $uriKey = 'users';

    public function fields(RestifyRequest $request): array
    {
        return [
            Field::make('id')->readonly(),
            Field::make('name')->required(),
            Field::make('email')->required(),
            Field::make('role')->rules('in:admin,repartidor')->required(),
            Field::make('password')->required()->hidden(), // hidden in response
        ];
    }
}
