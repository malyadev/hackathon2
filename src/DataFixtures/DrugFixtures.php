<?php

namespace App\DataFixtures;

use App\Entity\Drug;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class DrugFixtures extends Fixture
{
    private $faker;

    const DRUG_NUMBER=50;//note that drug references are set from drug_1 to drug_'DRUG_NUMBER'
    const MAX_FREQUENCY=5;
    const MAX_DOSE=1000;
    const MAX_DURATION=30;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $this->createDrug($manager);

        $manager->flush();
    }

    private function createDrug(ObjectManager $manager)
    {
        for ($i=1; $i<=self::DRUG_NUMBER; $i++) {
            $drug = new Drug();
            $drug->setName($this->faker->colorName);
            $drug->setFrequency($this->faker->numberBetween(1, self::MAX_FREQUENCY));
            $drug->setDose($this->faker->numberBetween(1, self::MAX_DOSE));
            $drug->setDuration($this->faker->numberBetween(1, self::MAX_DURATION));

            $manager->persist($drug);

            $this->addReference('drug_'.$i, $drug);
        }
    }
}
