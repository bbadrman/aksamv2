<?php

namespace App\EventSubscriber;

use App\Service\StatsService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigGlobalStatsSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private StatsService $statsService;

    public function __construct(Environment $twig, StatsService $statsService)
    {
        $this->twig = $twig;
        $this->statsService = $statsService;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $stats = $this->statsService->getStats();
        $this->twig->addGlobal('stats', $stats);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
