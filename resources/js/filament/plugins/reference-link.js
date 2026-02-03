import { Mark, mergeAttributes } from '@tiptap/core';

const ReferenceLink = Mark.create({
    name: 'referenceLink',

    addAttributes() {
        return {
            'referenceId': {
                default: null,
                parseHTML: element => element.getAttribute('referenceId'),
                renderHTML: attributes => {
                    if (!attributes['referenceId']) return {};
                    return { 'referenceId': attributes['referenceId'] };
                },
            },
            'term': {
                default: null,
                parseHTML: element => element.getAttribute('term'),
                renderHTML: attributes => {
                    if (!attributes['term']) return {};
                    return { 'term': attributes['term'] };
                },
            },
            'context': {
                default: null,
                parseHTML: element => element.getAttribute('context'),
                renderHTML: attributes => {
                    if (!attributes['context']) return {};
                    return { 'context': attributes['context'] };
                },
            },
        };
    },

    parseHTML() {
        return [{ tag: 'span[referenceId]' }];
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