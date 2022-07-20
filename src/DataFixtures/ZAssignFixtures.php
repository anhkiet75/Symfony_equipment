<?php

namespace App\DataFixtures;

use App\Entity\Assign;
use App\Entity\Category;
use App\Entity\Equipment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\EquipmentRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;

class ZAssignFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       
        for ($i = 0; $i < 5; $i++) {
            $assign = new Assign();
            $date = new DateTimeImmutable('2006-12-12');
            $assign->setDateAssign($date);
            $duedate = $date->modify('+20 day');
            $assign->setDueDate($duedate);
            
            $users = $manager->getRepository(User::class)->findAll();
            $user= $users[array_rand($users)];
            $assign->setUser($user);
            
            $equipmnets =$manager->getRepository(Equipment::class)->findAll();
            $equipment= $equipmnets[array_rand($equipmnets)];
            $assign->setEquipment($equipment);
            
            $manager->persist($assign);
        }
        $manager->flush();
    }
}
