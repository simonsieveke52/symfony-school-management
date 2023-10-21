<?php

namespace App\Security\Voters;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class StudentAccountVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    private $security;
     public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

          if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a entity object, thanks to `supports()`
        $entity = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($entity, $user);
            case self::EDIT:
                return $this->canEdit($entity, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(User $entity, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($entity, $user)) {
            return true;
        }

        // the entity object could have, for example, a method `isPrivate()`
        //return !$entity->isPrivate();
    }

    private function canEdit(User $entity, User $user)
    {
       
        	 return $user === $entity;
       
       
    }
}
