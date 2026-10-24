# Version 1.0.7 verification

Target: WordPress 7.1. Local PHP runtime: 8.2.12.

Passed:

- PHP syntax checks across the plugin.
- `php tests/client.php`: model request parameters, missing key, headline parsing, output limit, truncated/malformed responses, rate limits, and transport failures. HTTP responses are mocked; no paid API calls.
- `node tests/editor.cjs`: modern editor integration without accessing the deprecated API, legacy fallback, nonce-bearing generation request, and applying the returned title. WordPress globals are mocked.
- JavaScript syntax and `git diff --check`.
- Downloaded official WordPress 7.1 source: verified `wp.editor.PluginDocumentSettingPanel` export and `wp-editor` script registration. Reviewed the WordPress 7.1 field guide and unified editor API guidance.

User-reported verification:

- The maintainer tested the plugin locally on WordPress 7.1.3 and reported that generation works, with intermittent cURL error 35 connection resets. Version 1.0.7 adds one retry for that specific failure.
- `Tested up to: 7.1` reflects that report. This is not an independently performed browser test by the agent.
- Regression tests cover reset recovery, retry exhaustion, and no retries for certificate, timeout, authentication, or quota errors.

Additional recommended checks:

1. Install the ZIP on a WordPress 7.1 staging site, activate it, and save the settings.
2. With an API key that has access to `gpt-6-luna`, generate and apply suggestions in both post and page editors. Check browser console and PHP logs.
3. Verify an unauthorized user cannot call the generation REST endpoint.

The agent did not modify the pre-existing local WordPress installation. Live OpenAI generation was reported by the maintainer, not independently performed by the agent.

Sources:

- https://developers.openai.com/api/docs/models/gpt-6-luna
- https://developers.openai.com/api/docs/guides/latest-model
- https://make.wordpress.org/core/2024/06/18/editor-unified-extensibility-apis-in-6-6/
- https://make.wordpress.org/core/2026/08/05/wordpress-7-1-field-guide/
