<?php

namespace App\DataFixtures;

use App\Entity\Prescription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class PrescriptionFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    const PRESCRIPTION_NUMBER=50;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $this->createPrescription($manager);

        $manager->flush();
    }

    private function createPrescription(ObjectManager $manager)
    {
        for ($i=1; $i<=self::PRESCRIPTION_NUMBER; $i++) {
            $prescription = new Prescription();
            $patient='patient_'.rand(1, UserFixtures::PATIENT_NUMBER);
            $prescription->setUser($this->getReference($patient));
            $manager->persist($prescription);

            $this->addReference('prescription_'.$i, $prescription);
        }
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
