<?php

namespace App;

use Doctrine\ORM\EntityManagerInterface;

class MyService
{
    public function __construct(EntityManagerInterface $manager, int $booksPerPage)
    {
    }
}