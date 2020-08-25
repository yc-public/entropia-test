<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\User;

class UserExists implements Rule
{
    protected $role;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return User::where('id', $value)
        ->whereHas('role', function($query) {
            $query->where('name', $this->role);
        })
        ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is invalid';
    }
}
