<?php declare(strict_types=1);

namespace App\Handlers\Authentication;

use App\DTO\Authentication\RegistrationDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationHandler
{
    /**
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @param RegistrationDTO $dto
     * @return User
     */
    public function handle(RegistrationDTO $dto): User
    {
        $user = User::create(
            $dto->email,
            ['ROLE_USER'],
            $dto->password
        );

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);

        return $user;
    }
}
