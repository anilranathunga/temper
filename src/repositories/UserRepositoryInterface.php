<?php

namespace src\repositories;

interface UserRepositoryInterface
{
    /**
     * Get user data from data source
     * @return mixed
     */
    public function getUserData(): array;
}