<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;


class ProduitsVoter extends Voter
{
    public const EDIT = 'PRODUITS_EDIT';
    public const VIEW = 'PRODUITS_VIEW';
    public const CREATE = 'PRODUITS_CREATE';
    public const LIST = 'PRODUITS_LIST';

    protected function supports(string $attribute, mixed $subject): bool
    {
        
        return in_array($attribute, [self::CREATE, self::LIST]) || in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Produits;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $subject->getUser()->getId() === $user->getId();
                break;

            case self::VIEW:
            case self::LIST:
            case self::CREATE:
                return true;
                break;
        }

        return false;
    }
}
