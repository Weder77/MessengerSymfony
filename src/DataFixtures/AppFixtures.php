<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Create Users
        for ($i = 1; $i <= 3; $i++) {
            $user = new User;
            $user->setUsername('user' . $i);
            $password = $this->encoder->encodePassword($user, 'Ynov2020');
            $user->setPassword($password);
            $user->setEmail('user' . $i . '@user.loc');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
