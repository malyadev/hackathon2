<?php

namespace App\DataFixtures;

use App\Entity\Prescription;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DoctrineExtensions\Query\Mysql\Date;
use Faker;

class PrescriptionFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    const PRESCRIPTION_NUMBER=100;

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
            $creationDate = $this->faker->date('Y-m-d', 'now');
            $prescription->setCreation(new DateTime($creationDate));

            $patient='patient_'.rand(1, UserFixtures::PATIENT_NUMBER);
            $prescription->setUser($this->getReference($patient));

            $practitioner='practitioner_'.rand(1, UserFixtures::PRACTITIONER_NUMBER);
            $prescription->setPractitioner($this->getReference($practitioner));

            $buyDate= new DateTime();
            $preparationDate= new DateTime();

            $status = rand(1, 4);
            if ($status > 1) {
                $buyDate = $this->faker->date();
                $prescription->setBuy(new DateTime($buyDate));
                $pharmacist='pharmacist_'.rand(1, UserFixtures::PHARMACIST_NUMBER);
                $prescription->setPharmacist($this->getReference($pharmacist));
            }

            if ($status > 2) {
                $preparationDate = $this->faker->date();
                $prescription->setPreparation(new DateTime($preparationDate));
            }

            if ($status > 3) {
                $deliveryDate = $this->faker->date();
                $prescription->setDelivery(new DateTime($deliveryDate));
            }

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
