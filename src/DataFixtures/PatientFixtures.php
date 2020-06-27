<?php

namespace App\DataFixtures;

use App\Entity\Drug;
use App\Entity\Patient;
use App\Entity\Pharmacy;
use App\Entity\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class PatientFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;



    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($i=1; $i<=UserFixtures::PATIENT_NUMBER; $i++) {
            $user = $this->getReference('patient_'.$i);
            $index = rand(1, UserFixtures::PHARMACIST_NUMBER);
            $pharmacy = $this->getReference('pharmacy_1');
            $patient=new Patient();
            $patient->setPatient($user);
            $patient->setPharmacy($pharmacy);
            $manager->persist($patient);
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [PharmacyFixtures::class, UserFixtures::class];
    }
}
