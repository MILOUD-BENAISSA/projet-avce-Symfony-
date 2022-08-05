<?php

declare(strict_types=1);

namespace App\DTO;

class UserSearch
{
    public  int $limit =15;
    public  int $page =1;
    public  string $sortBy = 'id';
    public  string $direction = 'ASC';
}
