<?php

namespace App\Security\Voters;

use App\Entity\Prof;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ProfAccountVoter extends Voter
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

        
        if (!$subject instanceof Prof) {
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

        if (!$user instanceof Prof) {
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

    private function canView(Prof $entity, Prof $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($entity, $user)) {
            return true;
        }

        // the entity object could have, for example, a method `isPrivate()`
        //return !$entity->isPrivate();
    }

    private function canEdit(Prof $entity, Prof $user)
    {
       
        	 return $user === $entity;
       
       
    }
}
