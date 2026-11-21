<?php

namespace Drupal\montreal2027_auth\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\social_auth\Event\LoginEvent;
use Drupal\social_auth\Event\SocialAuthEvents;
use Drupal\user\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Syncs the Google profile name to field_name on every Google login.
 */
class UserNameSyncSubscriber implements EventSubscriberInterface {

  public function __construct(
    protected AccountProxyInterface $currentUser,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

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
  public function onUserLogin(LoginEvent $event): void {
    if ($event->getPluginId() !== 'social_auth_google') {
      return;
    }

    $google_name = $event->getSocialAuthUser()->getName();
    if (empty($google_name)) {
      return;
    }

    $uid = $this->currentUser->id();
    if (!$uid) {
      return;
    }

    /** @var \Drupal\user\UserInterface|null $user */
    $user = $this->entityTypeManager->getStorage('user')->load($uid);

    if ($user instanceof UserInterface && $user->hasField('field_name') && $user->get('field_name')->value !== $google_name) {
      $user->set('field_name', $google_name);
      $user->save();
    }
  }

}
