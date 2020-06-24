<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {

        $this->createUser("practitioner", $manager);
        $this->createUser("patient", $manager);
        $this->createUser("pharmacist", $manager);

        $manager->flush();
    }

    private function createUser(string $role, ObjectManager $manager, int $number = 10)
    {
        for ($i=0; $i<$number; $i++) {
            $user = new User();
            $user->setFirstname($this->faker->firstName);
            $user->setLastname($this->faker->lastName);
            $user->setEmail($role . $i . '@doctolib.fr');
            $user->setRoles(['ROLE_'.strtoupper($role)]);
            $user->setStatus('');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $role.'password'
            ));

            $manager->persist($user);
        }
    }
}
