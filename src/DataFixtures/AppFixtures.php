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
        $admin = new User();

        $password = $this->encoder->encodePassword($admin, '123456789');
        $admin
            ->setEmail('admin@animal.ads')
            ->setPassword($password)
            ->setEnabled(true)
            ->setGender('unknown')
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($admin);

        $user = new User();

        $password = $this->encoder->encodePassword($user, '123456789');
        $user
            ->setEmail('user@animal.ads')
            ->setPassword($password)
            ->setEnabled(true)
            ->setGender('unknown')
        ;

        $manager->persist($user);

        $manager->flush();
    }
}
