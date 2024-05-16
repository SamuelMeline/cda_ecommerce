<?php

namespace App\EventListener;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PageNotFindListener
{
    private $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if(!$exception instanceof NotFoundHttpException){
            return;
        }
        $error = $this->twig->render('notification/page_not_found.html.twig');
        $event->setResponse((new Response())->setContent($error));
    }
}
