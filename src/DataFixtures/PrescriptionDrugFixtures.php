<?php

namespace App\DataFixtures;

use App\Entity\Drug;
use App\Entity\Prescription;
use App\Entity\PrescriptionDrug;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class PrescriptionDrugFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    const PRESCRIPTION_DRUG_NUMBER=300;
    const ADVICES=[
        "Do not forget to drink water",
        "Avoid taking this drug if temperature is above 35°C",
        "Take after eating",
        "",
        "",
        "",
        "",
    ];

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

            if ($i<=PrescriptionFixtures::PRESCRIPTION_NUMBER) {
                $prescription='prescription_'.$i;
            } else {
                $prescription='prescription_'.rand(1, PrescriptionFixtures::PRESCRIPTION_NUMBER);
            }

            $prescriptionDrug->setPrescription($this->getReference($prescription));

            $drug='drug_'.rand(1, DrugFixtures::DRUG_NUMBER);
            $prescriptionDrug->setDrug($this->getReference($drug));

            $prescriptionDrug->setFrequency($this->faker->numberBetween(1, DrugFixtures::MAX_FREQUENCY));
            $prescriptionDrug->setDose($this->faker->numberBetween(1, DrugFixtures::MAX_DOSE));
            $prescriptionDrug->setDuration($this->faker->numberBetween(1, DrugFixtures::MAX_DURATION));

            $prescriptionDrug->setAdvice(self::ADVICES[array_rand(self::ADVICES)]);

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
