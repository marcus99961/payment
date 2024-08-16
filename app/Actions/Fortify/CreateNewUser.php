<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['required'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),

        ])->validate();
        if($input['department_id']==3){
            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'department_id'=>$input['department_id'],
                'role_id'=>'2',
                'password' => Hash::make($input['password']),

            ]);
        }else{
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'department_id'=>$input['department_id'],
            'password' => Hash::make($input['password']),

        ]);
        }
    }
}
