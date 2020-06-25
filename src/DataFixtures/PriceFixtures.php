<?php

namespace App\DataFixtures;

use App\Entity\Drug;
use App\Entity\Pharmacy;
use App\Entity\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class PriceFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;



    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $index=1;
        for ($i=1; $i<=UserFixtures::PHARMACIST_NUMBER; $i++) {
            $pharmacy = $this->getReference('pharmacy_'.$i);

            for ($j=1; $j<DrugFixtures::DRUG_NUMBER; $j++) {
                $drug = $this->getReference('drug_'.$j);

                $price= $this->createPrice($manager, $pharmacy, $drug);
                $this->addReference('price_'.$index, $price);
                $index++;
            }
        }

        $manager->flush();
    }

    private function createPrice(ObjectManager $manager, Pharmacy $pharmacy, Drug $drug) : Price
    {

            $price = new Price();

            $price->setPharmacy($pharmacy);
            $price->setDrug($drug);
            $basePrice=strlen($drug->getName());
            $coef=$this->faker->randomFloat(2, 0.7, 1.3);
            $price->setPrice($basePrice * $coef);

            $manager->persist($price);

            return $price;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [PharmacyFixtures::class, DrugFixtures::class];
    }
}
