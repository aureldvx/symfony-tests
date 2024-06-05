<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFunctionalTestCase extends WebTestCase
{
    public function login(KernelBrowser $client, string $email = 'email+0@domaine.fr'): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => $email]);
        $client->loginUser($testUser);
    }

    public function getAuthenticatedUser(KernelBrowser $client): ?User
    {
        /** @var User|null */
        return $client->getContainer()->get('security.token_storage')->getToken()->getUser();
    }
}
