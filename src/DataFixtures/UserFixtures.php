<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    private $faker;

    const PATIENT_NUMBER=50;//note that user references are set from user_1 to user_'USER_NUMBER'
    const PHARMACIST_NUMBER=15;
    const PRACTITIONER_NUMBER=15;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $this->createUser("practitioner", $manager, self::PRACTITIONER_NUMBER);
        $this->createUser("patient", $manager, self::PATIENT_NUMBER);
        $this->createUser("pharmacist", $manager, self::PHARMACIST_NUMBER);

        $manager->flush();
    }

    private function createUser(string $role, ObjectManager $manager, int $number)
    {
        for ($i=1; $i<=$number; $i++) {
            $user = new User();
            $user->setFirstname($this->faker->firstName);
            $user->setLastname($this->faker->lastName);
            $user->setEmail($role .'_'. $i . '@doctolib.fr');
            $user->setRoles(['ROLE_'.strtoupper($role)]);
            $user->setStatus('');
            $user->setSocialNumber($this->faker->randomNumber(5, true));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $role.'password'
            ));

            $manager->persist($user);

            $this->addReference($role.'_'.$i, $user);
        }
    }
}
