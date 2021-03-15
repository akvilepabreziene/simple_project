<?php

namespace App\DataFixtures;

use App\Constant\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function getOrder() : int
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $admin = (new User)
            ->setName('admin')
            ->setEmail('admin@admin.lt')
            ->addRole(Role::ROLE_ADMIN);
        $password = $this->passwordEncoder->encodePassword($admin,'demo');
        $admin->setPassword($password);
        $manager->persist($admin);
        $manager->flush();
    }
}
