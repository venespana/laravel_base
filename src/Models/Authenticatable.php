<?php

namespace Ximdex\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Authenticatable as TraitAuthenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Authenticatable extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Notifiable, TraitAuthenticatable, Authorizable, CanResetPassword;

    public static function create(array $attributes)
    {
        $attributes['api_token'] = User::generateToken();
        $attributes['password'] = Hash::make($attributes['password']);
        $model = static::query()->create($attributes);
        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $fillable = array_diff($this->fillable, ['email', 'username']);

        if (isset($attributes['password']) && !empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }
        $attributes = array_only($attributes, $fillable);

        return parent::update($attributes, $options);
    }
}