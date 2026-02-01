import { Mark, mergeAttributes } from '@tiptap/core';

const ReferenceLink = Mark.create({
    name: 'referenceLink',

    addAttributes() {
        return {
            'data-reference-id': {
                default: null,
                parseHTML: element => element.getAttribute('data-reference-id'),
                renderHTML: attributes => {
                    if (!attributes['data-reference-id']) return {};
                    return { 'data-reference-id': attributes['data-reference-id'] };
                },
            },
            'data-term': {
                default: null,
                parseHTML: element => element.getAttribute('data-term'),
                renderHTML: attributes => {
                    if (!attributes['data-term']) return {};
                    return { 'data-term': attributes['data-term'] };
                },
            },
            'data-context': {
                default: null,
                parseHTML: element => element.getAttribute('data-context'),
                renderHTML: attributes => {
                    if (!attributes['data-context']) return {};
                    return { 'data-context': attributes['data-context'] };
                },
            },
        };
    },

    parseHTML() {
        return [{ tag: 'span[data-reference-id]' }];
    },

    renderHTML({ HTMLAttributes }) {
        return ['span', mergeAttributes(HTMLAttributes, { class: 'reference-link' }), 0];
    },

    addCommands() {
        return {
            setReferenceLink: (attributes) => ({ commands }) => {
                return commands.setMark(this.name, attributes);
            },
            unsetReferenceLink: () => ({ commands }) => {
                return commands.unsetMark(this.name);
            },
        };
    },
});

export default ReferenceLink;