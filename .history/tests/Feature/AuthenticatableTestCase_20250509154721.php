<?php

namespace Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

abstract class AuthenticatableTestCase extends TestCase
{
    /**
     * Cast a model to an Authenticatable for testing
     * 
     * @param Model $user The user model to cast
     * @return Authenticatable
     */
    protected function castToAuthenticatable(Model $user): Authenticatable
    {
        // Ensure the model is actually an Authenticatable
        if ($user instanceof Authenticatable) {
            return $user;
        }
        
        // If we've reached here, throw an exception as we can't safely cast
        throw new \InvalidArgumentException('The provided model does not implement Authenticatable interface');
    }
}
