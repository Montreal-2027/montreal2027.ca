<?php

namespace Drupal\montreal2027_auth\EventSubscriber;

// use Drupal\social_auth\Event\LoginEvent;
use Drupal\social_auth\Event\SocialAuthEvents;
// use Drupal\social_auth\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Syncs the Google profile name to field_name on every Google login.
 */
class UserNameSyncSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      SocialAuthEvents::USER_LOGIN => ['onUserLogin'],
    ];
  }

  /**
   * Updates field_name when a user logs in via Google.
   */
  public function onUserLogin($event): void {
    if ($event->getPluginId() !== 'social_auth_google') {
      return;
    }

    $social_user = $event->getSocialAuthUser();
    $google_name = $social_user->getName();

    if (empty($google_name)) {
      return;
    }

    $user = $event->getUser();

    if ($user->hasField('field_name') && $user->get('field_name')->value !== $google_name) {
      $user->set('field_name', $google_name);
      $user->save();
    }
  }
}
