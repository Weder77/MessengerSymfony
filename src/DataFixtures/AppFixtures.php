<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create Users
        for ($i = 1; $i <= 3; $i++) {
            $user = new User;
            $user->setUsername('user' . $i);
            $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$EhOLiH7Tg0WPOdnd7UQwUQ$fWLoX7zHIniw/yUfA/ds9/2Nl893xZ9/mWln60GRjRQ');
            $user->setEmail('user' . $i . '@user.loc');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
