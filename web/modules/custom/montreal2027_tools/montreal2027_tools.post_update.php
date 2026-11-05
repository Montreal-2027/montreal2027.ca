<?php

declare(strict_types=1);

use Drupal\node\NodeAccessRebuild;

/**
 * Rebuild access grants for existing unpublished In Memoriam entries.
 */
function montreal2027_tools_post_update_in_memoriam_access(): void {
  \Drupal::getContainer()->get(NodeAccessRebuild::class)->rebuild();
}
