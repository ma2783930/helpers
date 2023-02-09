<?php

namespace Helpers\Traits;

use Illuminate\Support\Facades\Hash;

/**
 * @property string password
 * @property string|integer id
 */
trait HasPassword
{
    public function setUserPassword($password): static
    {
        $this->password = Hash::make(
            $password . $this->id
        );
        return $this;
    }

    public function checkUserPassword($password): bool
    {
        return Hash::check(
            $password . $this->id,
            $this->password
        );
    }
}
