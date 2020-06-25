<?php

namespace App\DataFixtures;

use App\Entity\Pharmacy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class PharmacyFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    const PHARMACY_NUMBER=UserFixtures::PHARMACIST_NUMBER;
    const IMAGE_NUMBER=7;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $this->createPharmacy($manager);

        $manager->flush();
    }

    private function createPharmacy(ObjectManager $manager)
    {
        for ($i=1; $i<=self::PHARMACY_NUMBER; $i++) {
            $city=$this->faker->city;
            $pharmacy = new Pharmacy();
            $pharmacy->setName('Pharmacie de '.$city);
            $pharmacy->setZipcode($this->faker->postcode);
            $pharmacy->setContact($this->getReference('pharmacist_'.$i));
            $pharmacy->setAddress($this->faker->streetAddress);
            $pharmacy->setCity($city);
            $pharmacy->setLatitude($this->faker->latitude(44, 50));
            $pharmacy->setLongitude($this->faker->latitude(-0.5, 6));
            $pharmacy->setPicture('image'.rand(1, self::IMAGE_NUMBER));

            $manager->persist($pharmacy);

            $this->addReference('pharmacy_'.$i, $pharmacy);
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
