<?php
namespace App\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the registration
 */
class RegistrationCompletedListener implements EventSubscriberInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'completeProfil',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'completeProfil',
            FOSUserEvents::REGISTRATION_SUCCESS => 'completeProfil',
        );
    }

    public function completeProfil($event)
    {
        $url = $this->router->generate('admin_user_complete_profile');
        $event->setResponse(new RedirectResponse($url));
    }
}