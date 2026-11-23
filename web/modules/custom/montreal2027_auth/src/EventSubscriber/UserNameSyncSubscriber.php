<?php

namespace Drupal\montreal2027_auth\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\social_auth\Event\LoginEvent;
use Drupal\social_auth\Event\SocialAuthEvents;
use Drupal\user\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Syncs Google given_name and family_name to Drupal user fields on login.
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
   * Updates first and last name fields when a user logs in via Google.
   */
  public function onUserLogin(LoginEvent $event): void {
    if ($event->getPluginId() !== 'social_auth_google') {
      return;
    }

    $uid = $this->currentUser->id();
    if (!$uid) {
      return;
    }

    /** @var \Drupal\user\UserInterface|null $user */
    $user = $this->entityTypeManager->getStorage('user')->load($uid);
    if (!$user instanceof UserInterface) {
      return;
    }

    $social_user = $event->getSocialAuthUser();
    $first_name = $social_user->getFirstName();
    $last_name = $social_user->getLastName();
    $needs_save = FALSE;

    if (!empty($first_name) && $user->hasField('field_first_name') && $user->get('field_first_name')->value !== $first_name) {
      $user->set('field_first_name', $first_name);
      $needs_save = TRUE;
    }

    if (!empty($last_name) && $user->hasField('field_last_name') && $user->get('field_last_name')->value !== $last_name) {
      $user->set('field_last_name', $last_name);
      $needs_save = TRUE;
    }

    if ($needs_save) {
      $user->save();
    }
  }
}
