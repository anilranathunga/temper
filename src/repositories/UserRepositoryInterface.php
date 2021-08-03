<?php

namespace src\repositories;

interface UserRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getUserData(): array;
}