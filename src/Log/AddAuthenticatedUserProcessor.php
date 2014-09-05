<?php

namespace Janus\ServiceRegistry\Log;

use Monolog\Logger as PsrLogger;

use Janus\ServiceRegistry\DependencyInjection\AuthenticationProviderInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Adds the name of the logged in user to the log entry metadata
 *
 * Class AddAuthenticatedUserProcessor
 * @package Janus\ServiceRegistry\Log
 */
class AddAuthenticatedUserProcessor extends ContainerAware
{
    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if (!$this->container || !$this->container->has('security.context')) {
            return $record;
        }

        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');
        /** @var TokenInterface $token */
        $token = $securityContext->getToken();

        if (!$token) {
            // Didn't start authentication yet
            return $record;
        }

        $username = $token->getUsername();
        $record['extra']['authenticated_username'] = $username;

        return $record;
    }
}
