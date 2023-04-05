<?php

namespace App\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddDateFieldListener implements EventSubscriberInterface
{
  public static function getSubscribedEvents(): array
  {
    return [
      FormEvents::PRE_SET_DATA => 'onPreSetData',
      FormEvents::POST_SET_DATA   => 'onPostSetData',
    ];
  }

  public function onPreSetData(FormEvent $event): void
  {
    $form = $event->getForm();
  }
}
