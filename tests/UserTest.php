<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new User())
            ->setEmail("jojo@laposte.net")
            ->setPassword("azerty")
            ->setRoles(["ROLE_USER"])
            ->setVerified(true);
    }

    public function getErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($user);
        $this->assertCount($number, $errors);
    }

    public function testValidEntity()
    {
        $this->getErrors($this->getEntity(), 0);
    }

    public function testInvalidEmail()
    {
        $this->getErrors($this->getEntity()->setEmail('')->setPassword(12), 1);
    }
}
