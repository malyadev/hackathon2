<?php


namespace App\Security;

use App\Entity\Prescription;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PrescriptionVoter extends Voter
{
    // these strings are just invented: you can use anything
    const EDIT = 'edit';
    const DELETE = 'delete';
    const READ = 'read';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::DELETE, self::READ])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Prescription) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Prescription $prescription */
        $prescription = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($prescription, $user);
            case self::EDIT:
                return $this->canEdit($prescription, $user);
            case self::READ:
                return $this->canRead($prescription, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(Prescription $prescription, User $user)
    {
        // copy canEdit
        return $this->canEdit($prescription, $user);
    }

    private function canEdit(Prescription $prescription, User $user)
    {
        if (in_array('ROLE_PRACTITIONER', $user->getRoles())) {
            return $user === $prescription->getPractitioner();
        }
        return false;
    }

    private function canRead(Prescription $prescription, User $user)
    {
        if (in_array('ROLE_PHARMACIST', $user->getRoles())) {
            return true;
        }

        if (in_array('ROLE_PRACTITIONER', $user->getRoles())) {
            return true;
        }

        // this assumes that the Post object has a `getOwner()` method
        return $user === $prescription->getUser();
    }
}
