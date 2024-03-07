<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CategoryFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName("Mouse");
        $category->setDescription("Cheap mosue");
        $manager->persist($category);

        $manager->flush();

        $category1 = new Category();
        $category1->setName("PC");
        $category1->setDescription("Cheap PC");
        $manager->persist($category1);

        $manager->flush();
        

        $category2 = new Category();
        $category2->setName("Laptop");
        $category2->setDescription("Laptop");
        $manager->persist($category2);

        $manager->flush();

        $category3 = new Category();
        $category3->setName("Monitor");
        $category3->setDescription("Monitor");
        $manager->persist($category3);

        $manager->flush();

        $this->addReference('mouse', $category);
        $this->addReference('pc', $category1);
        $this->addReference('laptop', $category2);
        $this->addReference('monitor', $category3);
    }
}
