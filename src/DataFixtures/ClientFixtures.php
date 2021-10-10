<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\ClientKind;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ClientFixtures extends Fixture
{
    protected Generator $faker;
    protected ObjectManager $manager;

    protected const CLIENTS_COUNT = 40;

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;
        $this->faker = Factory::create("pl_PL");
        $this->createFakeUsers(
            $this->createClientKinds()
        );
        $this->manager->flush();
    }

    protected function createFakeUsers(array $clientKinds)
    {
        for ($i = 0; $i < $this::CLIENTS_COUNT; $i++) {
            $client = new Client();
            $client->setFirstName($this->faker->firstName);
            $client->setLastName($this->faker->lastName);
            $client->setEmail($this->faker->email);
            $client->setKind($clientKinds[array_rand($clientKinds, 1)]);
            $this->manager->persist($client);
        }
    }
    public function createClientKinds(): array
    {
        $kinds = [
            (new ClientKind())->setName('individual'),
            (new ClientKind())->setName('business')
        ];

        foreach ($kinds as $kind) {
            $this->manager->persist($kind);
        }

        return $kinds;
    }
}
