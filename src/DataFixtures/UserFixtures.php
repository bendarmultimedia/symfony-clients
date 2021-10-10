<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;
    private $adminEmail;
    private $adminPlainPassword;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->checkAdminAuthenticationDetails();
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail($this->adminEmail);
        $user->setRoles(['ROLE_ADMIN']);

        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $this->adminPlainPassword
        ));
        $manager->persist($user);

        $manager->flush();
    }

    private function checkAdminAuthenticationDetails()
    {
        $this->adminEmail = (isset($_SERVER['ADMIN_EMAIL'])) ? $_SERVER['ADMIN_EMAIL'] : '' ;
        $this->adminPlainPassword = (isset($_SERVER['ADMIN_PASSWORD'])) ? $_SERVER['ADMIN_PASSWORD'] : '' ;
        if (!filter_var($this->adminEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception(
                "Set the correct email address for ADMIN_EMAIL variable in .env file for dev environment.",
                500
            );
        }
    }
}
