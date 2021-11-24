<?php

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserLeagacySubscriber implements EventSubscriberInterface
{

    private $userRepository;
    private $security;
    const ROUTESARRAY = ['home_index'];

    public function __construct(UserRepository $userRepository, Security $security){
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::REQUEST => ['onRequest'],
        ];
    }

    
    public function onRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        
        if ( in_array($request->attributes->get('_route'), self::ROUTESARRAY) ) {
            
            $session = $request->getSession();
            
            /** @var User $user */
            $user = $this->security->getUser();
            $userId = $user->getId();
            
            $userLegacyIncluded = $this->userRepository->userLegacyIncluded($userId); 
            $userLegacyIncluded_array = $this->extractId($userLegacyIncluded);
            
            $userLegacyExcluded = $this->userRepository->userLegacyExcluded($userId);
            $userLegacyExcluded_array = $this->extractId($userLegacyExcluded);

            $session->set('userLegacyWithUser', $userLegacyIncluded_array);
            $session->set('userLegacyUserLess', $userLegacyExcluded_array);
            
        }
    }
    
    private function extractId($result){
        $array = [];
        foreach ($result as $value) {
            array_push($array, $value['id']);
        }
        return $array;
    }
}