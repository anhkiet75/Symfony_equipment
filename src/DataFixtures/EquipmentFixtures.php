<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Equipment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class EquipmentFixtures extends Fixture
{
    protected $faker;
    private static $randomCategory = [
        'mouse',
        'pc',
        'laptop',
        'monitor',
    ];

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        for ($i=0; $i< 10; $i++) {
            $equipment = new Equipment();
            $category = $this->faker->randomElement(self::$randomCategory);
            $equipment->setCategory(
                $this->getReference($category)
            );
            $equipment->setName($category. $this->faker->ean8);
            $equipment->setDescription($category);
            $equipment->setStatus("AVAILABLE");
            $manager->persist($equipment);
        }
        $manager->flush();
    }
}
