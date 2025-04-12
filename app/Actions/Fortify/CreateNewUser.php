<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'role' => '',
            'pID' => 0,
            'kldID' => 0,
            'fName' =>'',
            'lName' =>'',
            'mName' =>'',
            'img' =>'',
            'address' =>'',
            'gender' =>'',
            'bday' =>'',
            'ay'=>'',
            'section'=>'',
            'password' => Hash::make($input['password']),
        ]);
    }

    public function teachercreate(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'role' => 'teacher',
            'pID' => 0,
            'kldID' => 0,
            'fName' =>'',
            'lName' =>'',
            'mName' =>'',
            'img' =>'',
            'address' =>'',
            'gender' =>'',
            'bday' =>'','ay'=>'','section'=>'',
            'password' => Hash::make($input['password']),
        ]);
    }
}
