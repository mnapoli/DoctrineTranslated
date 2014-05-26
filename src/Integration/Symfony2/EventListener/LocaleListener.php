<?php

namespace Mnapoli\Translated\Integration\Symfony2\EventListener;

use Mnapoli\Translated\Translator;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * LocaleListener
 */
class LocaleListener
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        if ($request->getLocale()) {
            $this->translator->setLanguage($request->getLocale());
        }
    }
}
