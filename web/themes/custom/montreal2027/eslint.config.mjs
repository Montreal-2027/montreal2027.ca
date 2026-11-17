import js from '@eslint/js';

export default [
  js.configs.recommended,
  {
    files: ['**/*.js'],
    languageOptions: {
      globals: {
        Drupal: 'readonly',
        drupalSettings: 'readonly',
        once: 'readonly',
        document: 'readonly',
        window: 'readonly',
        fetch: 'readonly',
      },
    },
    rules: {
      'no-restricted-globals': ['error', '$', 'jQuery'],
    },
  },
];
