export function formatMultilineMessage(message = '') {
    const fragment = document.createDocumentFragment();
    const parts = String(message).split('\n');

    parts.forEach((part, index) => {
        fragment.appendChild(document.createTextNode(part));
        if (index < parts.length - 1) {
            fragment.appendChild(document.createElement('br'));
        }
    });

    return fragment;
}