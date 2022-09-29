<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    public const EDIT = 'movie.edit';
    public const VIEW = 'movie.view';

    private AuthorizationCheckerInterface $checker;

    public function __construct(AuthorizationCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        assert($subject instanceof Movie);
        $user = $token->getUser();
        if (!$user instanceof User && !$user instanceof InMemoryUser) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->checkEdit($subject, $user);
            case self::VIEW:
                return $this->checkView($subject, $user);
        }

        return false;
    }

    private function checkView(Movie $movie, UserInterface $user): bool
    {
        if ('G' === $movie->getRated() || $this->checker->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if (!$user instanceof User || null === $user->getBirthday()) {
            return false;
        }

        $age = $user->getBirthday()->diff(new \DateTimeImmutable())->y;
        switch ($movie->getRated()) {
            case 'PG':
            case 'PG-13':
                return $age >= 13;
            case 'R':
            case 'NC-17':
                return $age >= 17;
        }

        return false;
    }

    private function checkEdit(Movie $movie, UserInterface $user): bool
    {
        return $this->checker->isGranted('ROLE_ADMIN')
            || ($this->checkView($movie, $user) && $user === $movie->getCreatedBy());
    }
}
