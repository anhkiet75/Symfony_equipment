<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Generator;
use Faker\Factory;

class RoleFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private $faker;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create();
    }
    
    public function load(ObjectManager $manager): void
    {  
        $user = new User();
        $user->setEmail("admin@gmail.com");
        $user->setGender(true);
        $user->setBirthdate(new \DateTime('2000-01-01'));
        $password = $this->hasher->hashPassword($user, '123456');
        $user->setPassword($password);
        $user->setName("admin");
        $manager->persist($user);

        $manager->flush();

        $user2 = new User();
        $user2->setEmail("user@gmail.com");
        $user2->setGender(false);
        $user2->setBirthdate(new \DateTime('2000-01-01'));
        $password = $this->hasher->hashPassword($user2, '123456');
        $user2->setPassword($password);
        $user2->setName("user");
        $manager->persist($user2);

        for ($i=0; $i < 10;$i++) {
            $user3 = new User();
            $user3->setEmail($this->faker->email);
            $user3->setGender($this->faker->boolean);
            $user3->setBirthdate(new \Datetime($this->faker->date($format = 'Y-m-d', $max = 'now')));
            $password = $this->hasher->hashPassword($user3, '123456');
            $user3->setPassword($password);
            $user3->setName($this->faker->name);
            $manager->persist($user3);
        }

        $manager->flush();

        $role = new Role();
        $role->setName('ROLE_ADMIN');
        $role->setDescription('full access');
        $role->addUser($user);
        $manager->persist($role);

        $manager->flush();

        $role = new Role();
        $role->setName('ROLE_USER');
        $role->setDescription('only view own equipment');
        $role->addUser($user2);
        $manager->persist($role);

        $manager->flush();

    }
}
