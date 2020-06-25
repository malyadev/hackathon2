<?php

namespace App\DataFixtures;

use App\Entity\Drug;
use App\Entity\PrescriptionDrug;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class PrescriptionDrugFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    const PRESCRIPTION_DRUG_NUMBER=300;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $this->createPrescriptionDrug($manager);

        $manager->flush();
    }

    private function createPrescriptionDrug(ObjectManager $manager)
    {
        for ($i=1; $i<=self::PRESCRIPTION_DRUG_NUMBER; $i++) {
            $prescriptionDrug = new PrescriptionDrug();

            $prescription='prescription_'.rand(1, PrescriptionFixtures::PRESCRIPTION_NUMBER);
            $prescriptionDrug->setPrescription($this->getReference($prescription));

            $drug='drug_'.rand(1, DrugFixtures::DRUG_NUMBER);
            $prescriptionDrug->setDrug($this->getReference($drug));

            $prescriptionDrug->setFrequency($this->faker->numberBetween(1, DrugFixtures::MAX_FREQUENCY));
            $prescriptionDrug->setDose($this->faker->numberBetween(1, DrugFixtures::MAX_DOSE));
            $prescriptionDrug->setDuration($this->faker->numberBetween(1, DrugFixtures::MAX_DURATION));

            $manager->persist($prescriptionDrug);

            $this->addReference('prescription_drug_'.$i, $prescriptionDrug);
        }
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [PrescriptionFixtures::class, DrugFixtures::class];
    }
}
