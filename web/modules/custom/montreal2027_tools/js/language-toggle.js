(function (Drupal, drupalSettings, once) {

  /**
   * @file
   * Client-side EN/FR language toggle behaviour.
   *
   * Reads drupalSettings.languageToggle (populated by LanguageToggleBlock and
   * montreal2027_tools_preprocess_block) and reinforces active-state markup so
   * the toggle remains correct after any client-side navigation or AJAX update.
   */

  Drupal.behaviors.languageToggle = {
    attach(context) {
      const config = (drupalSettings && drupalSettings.languageToggle) || {};
      const currentLang = config.currentLang || document.documentElement.lang || 'en';
      const labels = config.labels || {};

      once('language-toggle', '.js-language-toggle', context).forEach((wrapper) => {
        // Reinforce active state driven by drupalSettings (handles AJAX re-attach).
        wrapper.querySelectorAll('.language-toggle__link[data-langcode]').forEach((link) => {
          const langcode = link.dataset.langcode;
          const isActive = langcode === currentLang;

          link.classList.toggle('is-active', isActive);
          link.setAttribute('aria-current', isActive ? 'true' : 'false');

          // Enrich the link label with canonical display name + flag image.
          if (labels[langcode]) {
            const name = labels[langcode].name || link.textContent.trim();
            const flagUrl = labels[langcode].flagUrl || '';

            link.textContent = '';

            const textNode = document.createElement('span');
            textNode.className = 'language-toggle__text';
            textNode.textContent = name;
            link.appendChild(textNode);

            if (flagUrl) {
              const flagImage = document.createElement('img');
              flagImage.className = 'language-toggle__flag';
              flagImage.src = flagUrl;
              flagImage.alt = '';
              flagImage.setAttribute('aria-hidden', 'true');
              flagImage.decoding = 'async';
              flagImage.loading = 'lazy';
              link.appendChild(flagImage);
            }
          }
        });

        // Persist language preference and provide smooth visual feedback.
        wrapper.addEventListener('click', (event) => {
          const link = event.target.closest('.language-toggle__link[data-langcode]');
          if (!link) {
            return;
          }

          try {
            localStorage.setItem('montreal2027_preferred_lang', link.dataset.langcode);
          }
          catch (e) {
            console.warn('Montreal 2027: Could not save preferred language to localStorage.', e);
          }
        });
      });
    },
  };

})(Drupal, drupalSettings, once);
