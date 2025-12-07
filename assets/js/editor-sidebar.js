(function (wp) {
    const { registerPlugin } = wp.plugins;
    const { PluginDocumentSettingPanel } = wp.editPost;
    const { PanelBody, Button, Spinner, Notice } = wp.components;
    const { useSelect, useDispatch } = wp.data;
    const { useState, createElement, Fragment } = wp.element;
    const apiFetch = wp.apiFetch;

    // Scoring function -------------------------------------------------

    function scoreHeadline(headline) {
        const text = headline.trim();
        const length = text.length;
        const words = text.split(/\s+/).filter(Boolean);
        const wordCount = words.length;

        let score = 70; // base

        // Length: ideal 40–70 chars.
        if (length >= 40 && length <= 70) {
            score += 10;
        } else {
            const dist =
                length < 40 ? 40 - length : length > 70 ? length - 70 : 0;
            score -= Math.min(dist, 20);
        }

        // Word count: ideal 6–12.
        if (wordCount >= 6 && wordCount <= 12) {
            score += 10;
        } else {
            const dist =
                wordCount < 6 ? 6 - wordCount : wordCount > 12 ? wordCount - 12 : 0;
            score -= Math.min(dist * 2, 15);
        }

        // Bonus: has number.
        if (/\d/.test(text)) {
            score += 4;
        }

        // Bonus: has colon/dash separator.
        if (/[ :-–—]/.test(text) && /[:\-–—]/.test(text)) {
            score += 4;
        }

        // Bonus: hook words.
        const hooks = [
            'how',
            'why',
            'what',
            'guide',
            'tips',
            'tricks',
            'secrets',
            'simple',
            'easy',
            'ultimate',
            'step-by-step',
            'pro',
        ];
        const lower = text.toLowerCase();
        let hookHits = 0;
        hooks.forEach((w) => {
            if (lower.includes(w)) hookHits++;
        });
        score += Math.min(hookHits * 2, 10);

        if (score > 100) score = 100;
        if (score < 0) score = 0;

        return Math.round(score);
    }

    // Panel ------------------------------------------------------------

    const HeadlineBoosterPanel = () => {
        const [variants, setVariants] = useState([]); // [{ text, score }]
        const [loading, setLoading] = useState(false);
        const [error, setError] = useState(null);

        const title = useSelect(
            (select) => select('core/editor').getEditedPostAttribute('title'),
            []
        );

        const excerpt = useSelect(
            (select) => select('core/editor').getEditedPostAttribute('excerpt'),
            []
        );

        const { editPost } = useDispatch('core/editor');

        const generate = () => {
            setLoading(true);
            setError(null);
            setVariants([]);

            apiFetch({
                url: HBAITSSettings.apiUrl,
                method: 'POST',
                headers: {
                    'X-WP-Nonce': HBAITSSettings.nonce,
                },
                data: {
                    title,
                    excerpt,
                },
            })
                .then((response) => {
                    if (!response || !response.variants) {
                        throw new Error('No variants returned from API.');
                    }

                    const scored = response.variants.map((v) => ({
                        text: v,
                        score: scoreHeadline(v),
                    }));

                    scored.sort((a, b) => b.score - a.score);

                    setVariants(scored);
                })
                .catch((err) => {
                    console.error('Headline Booster error:', err);

                    const message =
                        (err && err.data && err.data.debug) ||
                        (err && err.message) ||
                        'Could not generate headline variants.';

                    setError(message);
                })
                .finally(() => {
                    setLoading(false);
                });
        };

        const useVariant = (variantText) => {
            editPost({ title: variantText });
        };

        const bestScore = variants.length ? variants[0].score : null;

        return createElement(
            PluginDocumentSettingPanel,
            {
                name: 'headline-booster-panel',
                title: 'Headline Booster',
            },
            createElement(
                PanelBody,
                null,
                createElement(
                    'p',
                    null,
                    createElement('strong', null, 'Current title:')
                ),
                createElement('p', null, title || '(no title yet)'),

                createElement(
                    Button,
                    {
                        isPrimary: true,
                        onClick: generate,
                        disabled: !title || loading,
                        style: { marginBottom: '0.5em' },
                    },
                    loading ? 'Generating…' : 'Generate Variants'
                ),

                loading ? createElement(Spinner, null) : null,

                error
                    ? createElement(
                          Notice,
                          { status: 'error', isDismissible: false },
                          error
                      )
                    : null,

                variants.length
                    ? createElement(
                          Fragment,
                          null,
                          createElement(
                              'h3',
                              { style: { marginTop: '1em' } },
                              'Suggestions'
                          ),
                          createElement(
                              'ul',
                              { style: { listStyle: 'none', paddingLeft: 0 } },
                              variants.map((v, index) =>
                                  createElement(
                                      'li',
                                      {
                                          key: index,
                                          style: {
                                              marginBottom: '0.9em',
                                              borderBottom:
                                                  index === variants.length - 1
                                                      ? 'none'
                                                      : '1px solid #eee',
                                              paddingBottom: '0.6em',
                                          },
                                      },
                                      createElement(
                                          'div',
                                          {
                                              style: {
                                                  display: 'flex',
                                                  alignItems: 'center',
                                                  justifyContent: 'space-between',
                                                  gap: '8px',
                                                  marginBottom: '0.25em',
                                              },
                                          },
                                          createElement(
                                              'span',
                                              { style: { flex: 1 } },
                                              '“' + v.text + '”'
                                          ),
                                          createElement(
                                              'span',
                                              null,
                                              createElement(
                                                  'span',
                                                  {
                                                      isPrimary:
                                                          v.score === bestScore,
                                                  },
                                                  v.score + '/100'
                                              )
                                          )
                                      ),
                                      v.score === bestScore
                                          ? createElement(
                                                'p',
                                                {
                                                    style: {
                                                        fontSize: '11px',
                                                        margin:
                                                            '0 0 0.25em 0',
                                                        color: '#008a20',
                                                    },
                                                },
                                                'Recommended (highest score)'
                                            )
                                          : null,
                                      createElement(
                                          Button,
                                          {
                                              isSecondary: true,
                                              size: 'small',
                                              onClick: () => useVariant(v.text),
                                          },
                                          'Use this title'
                                      )
                                  )
                              )
                          )
                      )
                    : null
            )
        );
    };

    registerPlugin('headline-booster', {
        render: HeadlineBoosterPanel,
        icon: 'edit',
    });
})(window.wp);
