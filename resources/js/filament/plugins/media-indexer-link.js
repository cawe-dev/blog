import { Mark, mergeAttributes } from '@tiptap/core';

const MediaIndexerLink = Mark.create({
    name: 'mediaIndexerLink',

    addAttributes() {
        return {
            type: {
                default: null,
                parseHTML: (element) => element.getAttribute('type'),
                renderHTML: (attributes) => {
                    if (!attributes['type']) return {};
                    return { type: attributes['type'] };
                },
            },
            url: {
                default: null,
                parseHTML: (element) => element.getAttribute('url'),
                renderHTML: (attributes) => {
                    if (!attributes['url']) return {};
                    return { url: attributes['url'] };
                },
            },
        };
    },

    parseHTML() {
        return [{ tag: 'span[url]' }];
    },

    renderHTML({ HTMLAttributes }) {
        return ['span', mergeAttributes(HTMLAttributes, { class: 'media-indexer-link' }), 0];
    },

    addCommands() {
        return {
            setMediaIndexerLink:
                (attributes) =>
                ({ commands }) => {
                    return commands.setMark(this.name, attributes);
                },
            unsetMediaIndexerLink:
                () =>
                ({ commands }) => {
                    return commands.unsetMark(this.name);
                },
        };
    },
});

export default MediaIndexerLink;
