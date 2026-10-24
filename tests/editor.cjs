const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const source = fs.readFileSync(__dirname + '/../assets/js/editor-sidebar.js', 'utf8');
async function run(modern) {
    let plugin, request, edited;
    const states = [];
    let cursor = 0;
    const Panel = () => {};
    const wp = {
        plugins: { registerPlugin: (name, options) => { plugin = options; } },
        editor: modern ? { PluginDocumentSettingPanel: Panel } : {},
        editPost: modern ? new Proxy({}, { get() { throw Error('Deprecated API accessed'); } }) : { PluginDocumentSettingPanel: Panel },
        components: { PanelBody: 'body', Button: 'button', Spinner: 'spinner', Notice: 'notice' },
        data: {
            useSelect: fn => fn(() => ({ getEditedPostAttribute: name => name === 'title' ? 'Original title' : '' })),
            useDispatch: () => ({ editPost: value => { edited = value; } })
        },
        element: {
            useState: initial => { const index = cursor++; if (!(index in states)) states[index] = initial; return [states[index], value => { states[index] = value; }]; },
            createElement: (type, props, ...children) => ({ type, props, children }), Fragment: 'fragment'
        },
        apiFetch: async args => { request = args; return { variants: ['10 Ways to Write Better Blog Headlines Today'] }; }
    };
    vm.runInNewContext(source, { window: { wp }, HBAITSSettings: { apiUrl: '/generate', nonce: 'nonce' }, console });
    function render() { cursor = 0; return plugin.render(); }
    function find(node, predicate) {
        if (!node || typeof node !== 'object') return;
        if (predicate(node)) return node;
        for (const child of (Array.isArray(node) ? node : node.children || [])) { const match = find(child, predicate); if (match) return match; }
    }
    const tree = render();
    assert.equal(tree.type, Panel);
    find(tree, n => n.type === 'button' && n.props.onClick).props.onClick();
    await new Promise(resolve => setImmediate(resolve));
    assert.equal(request.data.title, 'Original title');
    assert.equal(request.headers['X-WP-Nonce'], 'nonce');
    const updated = render();
    find(updated, n => n.type === 'button' && n.children.includes('Use this title')).props.onClick();
    assert.equal(edited.title, '10 Ways to Write Better Blog Headlines Today');
}
(async () => { await run(true); await run(false); console.log('Modern and legacy editor generate/apply checks passed.'); })().catch(error => { console.error(error); process.exitCode = 1; });
