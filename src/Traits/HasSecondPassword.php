<?php

namespace Helpers\Traits;

use Illuminate\Support\Facades\Hash;

/**
 * @property string second_password
 * @property string|integer id
 */
trait HasSecondPassword
{
    private string $secondPasswordSuffix = 'second';

    public function setUserSecondPassword($password): static
    {
        $this->second_password = Hash::make(
            $password . $this->id . str($this->secondPasswordSuffix)->reverse()
        );
        return $this;
    }

    public function checkUserSecondPassword($password): bool
    {
        return Hash::check(
            $password . $this->id . str($this->secondPasswordSuffix)->reverse(),
            $this->second_password
        );
    }
}
