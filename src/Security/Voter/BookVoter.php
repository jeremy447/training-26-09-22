<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookVoter extends Voter
{
    public const EDIT = 'book.edit';
    public const VIEW = 'book.view';

    private AuthorizationCheckerInterface $checker;

    public function __construct(AuthorizationCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return self::EDIT === $attribute
            && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        assert($subject instanceof Book);
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->checker->isGranted('ROLE_ADMIN') || $user === $subject->getCreatedBy();
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
