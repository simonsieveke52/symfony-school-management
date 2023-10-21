<?php

namespace App\Events;

use App\Entity\User;
use App\Message\EmailNotification;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class UserConfurmationEmailListener
{
    private $bus;

    function __construct(MessageBusInterface $bus)
    {
    	$this->bus = $bus;
    } 

    public function postPersist(User $user, LifecycleEventArgs $event)
    {       
          $this->bus->dispatch(new EmailNotification($user->getEmail()));
      
    }
}
