<?php

namespace Drupal\montreal2027_tools\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles AJAX submission for staff contact messages.
 */
class StaffContactController extends ControllerBase
{
  const FAILURE_MESSAGE = 'failure_message';
  const FAILURE_MESSAGE_EMAIL = 'failure_message_email';
  const SUCCESS_MESSAGE = 'success_message';
  const SUBJECT_PREFIX = 'subject_prefix';

  var $translated_content = [];
  var $langcode = '';

  /**
   * Constructs the contact controller.
   */
  public function __construct(
    private readonly MailManagerInterface $mailManager,
    private readonly EntityTypeManagerInterface $typedEntityManager,
    private readonly LanguageManagerInterface $siteLanguageManager,
    private readonly LoggerChannelFactoryInterface $logChannelFactory,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('plugin.manager.mail'),
      $container->get('entity_type.manager'),
      $container->get('language_manager'),
      $container->get('logger.factory'),
    );
  }

  /**
   * Submits a contact message to the staff term's email address.
   * 
   * @param Request $request
   * @return JsonResponse
   */
  public function submit(Request $request): JsonResponse
  {
    $this->langcode = $this->siteLanguageManager->getCurrentLanguage()->getId();
    $this->getTranslatedMessages();

    try {
      $submission = $this->getSubmittedData($request->request);
      $this->sendContactEmail($submission);

      return new JsonResponse([
        'success' => TRUE,
        'message' => $this->translated_content[self::SUCCESS_MESSAGE],
      ]);

    } catch (Exception $e) {
      return new JsonResponse([
        'success' => FALSE,
        // Return a general failure message unless a specific one has been thrown
        'message' => $e->getMessage() ?: $this->translated_content[self::FAILURE_MESSAGE],
      ], $e->getCode());
    }
  }

  /**
   * Send a contact email to the selected Team member
   * 
   * @param array $submission
   * @throws Exception
   * @return JsonResponse
   */
  private function sendContactEmail(array $submission) : void {
    $email_to = $this->getEmailTo($submission['term']);
    $subject = $this->getSubject($submission['tid']);

    $result = $this->mailManager->mail(
      'montreal2027_tools',
      'staff_contact',
      $email_to,
      $this->langcode,
      [
        'subject' => $subject,
        'body' => $submission['message'],
        'from_name' => $submission['name'],
        'from_email' => $submission['email'],
      ],
      $submission['email'],
      TRUE,
    );

    if (empty($result['result'])) {
      // Something went wrong sending the email
      $this->logChannelFactory->get('montreal2027_tools')->error('Failed to send staff contact email for term ID @tid.', [
        '@tid' => $submission['tid'],
      ]);

      throw new Exception('', 500);
    }
  }

  /**
   * Get and validate the submitted data
   * 
   * @param Object $request
   * @throws Exception
   * @return void
   */
  private function getSubmittedData(Object $request): array {
    $result = [
      'tid' => (int) $request->get('tid', 0),
      'name' => trim((string) $request->get('name', '')),
      'email' => trim((string) $request->get('email', '')),
      'message' => trim((string) $request->get('message', '')),
    ];

    $result['term'] = $this->getTargetTerm($result['tid']);

    // A dot-less domain like "localhost" is officially valid, but filter_var()
    // doesn't like it. For our purposes, we don't need to accept them, so we 
    // return it as an error
    if (!filter_var($result['email'], FILTER_VALIDATE_EMAIL)) {
      throw new Exception($this->translated_content[self::FAILURE_MESSAGE_EMAIL], 500);
    }

    // Prevent header injection from line breaks in user-entered name.
    $safe_name = preg_replace('/[\r\n]+/', ' ', $result['name']) ?? '';
    $safe_name = trim($safe_name);
    if ($safe_name === '') {
      throw new Exception('', 400);
    }

    // Require all fields to be non-empty
    if (
      $result['tid'] <= 0
      || $result['name'] === ''
      || $result['email'] === ''
      || $result['message'] === ''
    ) {
      throw new Exception('', 400);
    }

    return $result;
  }

  /**
   * Get the taxonomy term for the target of this Contact Message
   * 
   * @param int $tid
   * @throws Exception
   * @return \Drupal\taxonomy\TermInterface
   */
  private function getTargetTerm(int $tid) : Object {
    /** @var \Drupal\taxonomy\TermInterface|null $term */
    $term = $this->typedEntityManager->getStorage('taxonomy_term')->load($tid);
    if (!$term || !$term->hasField('field_email_address') || $term->get('field_email_address')->isEmpty()) {
      throw new Exception('', 404);
    }

    return $term;
  }

  /**
   * Get the email address the Contact Message is being sent to, based on the
   * target's taxonomy term
   * 
   * @param object $term
   * @throws Exception
   * @return string
   */
  private function getEmailTo(Object $term): string {
    $result = '';

    foreach ($term->get('field_email_address') as $item) {
      $candidate = trim((string) $item->value);
      if ($candidate !== '' && filter_var($candidate, FILTER_VALIDATE_EMAIL)) {
        $result = $candidate;
        break;
      }
    }

    if ($result === '') {
      throw new Exception('', 404);
    }

    return $result;
  }

  /**
   * Get the subject of the Contact Message email
   * 
   * @param int $tid
   * @return string
   */
  private function getSubject(int $tid): string {
    $division_label = $this->getTranslatedTerm($tid);
    $prefix = $this->translated_content[self::SUBJECT_PREFIX];
    if ($prefix == '') {
      return $division_label;
    }

    return rtrim($prefix) . ' ' . $division_label;
  }

  /**
   * Get the translated text for a taxonomy term
   * 
   * @param int $tid
   * @return string
   */
  private function getTranslatedTerm(int $tid) : string {
    /** @var \Drupal\taxonomy\TermStorageInterface $term_storage */
    $term_storage = $this->typedEntityManager->getStorage('taxonomy_term');

    $parents = $term_storage->loadAllParents($tid);
    
    // The parents are returned with the current term first, and the top parent at the end
    $top_ancestor = array_pop($parents);
    $translated_term = \Drupal::service('entity.repository')->getTranslationFromContext($top_ancestor, $this->langcode);
    return $translated_term->getName();
  }

  /**
   * Get the messages used in the email and also being returned to the sender (in the browser)
   * 
   * @return void
   */
  private function getTranslatedMessages(): void {
    $messages = [
      self::FAILURE_MESSAGE => 'There was a problem sending your message.  Please try again later.',
      self::SUCCESS_MESSAGE => 'Your message has been sent.  A member of our staff will reach out if appropriate.',
      self::SUBJECT_PREFIX => 'Staff contact message for ',
      self::FAILURE_MESSAGE_EMAIL => 'Please enter a valid email address.',
    ];

    $config = $this->config('montreal2027_tools.staff_page_settings');

    // If there is a message in the montreal2027 configuration use it, otherwise
    // use the default message above
    $this->translated_content = [];
    foreach ($messages as $msg_id => $default_message) {
      $translation = trim((string) ($config->get($msg_id) ?? ''));
      $this->translated_content[$msg_id] = $this->t($translation ?: $default_message);
    }
  }
}