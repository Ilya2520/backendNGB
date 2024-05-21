<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class CorsListener implements EventSubscriberInterface
{
    private $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', 9999],
            KernelEvents::EXCEPTION => ['onKernelException', 9999],
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
        ];
    }
    
    public function onKernelResponse(ResponseEvent $event): void
    {
        if ($event->getRequest()->getMethod() === 'OPTIONS') {
            $event->setResponse(
                new Response('', 204, [
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                    'Access-Control-Allow-Headers' => 'DNT, X-User-Token, Keep-Alive, User-Agent, 
                        X-Requested-With, If-Modified-Since, Cache-Control, Content-Type Authorization, Accept-Encoding',
                    'Access-Control-Max-Age' => 1728000,
                    'Content-Type' => 'text/plain charset=UTF-8',
                    'Content-Length' => 0
                ])
            );
            return ;
        }
    }
    
    public function onKernelException(ExceptionEvent $event): void
    {
        //$this->logger->info('CorsListener: onKernelException method called');
        // Your exception handling logic here
    }
    
    public function onKernelRequest(RequestEvent $event): void
    {
        //$this->logger->info('CorsListener: onKernelRequest method called');
        // Your request handling logic here
    }
}
