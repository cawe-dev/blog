import { Mark, mergeAttributes } from '@tiptap/core';

const HasSpoilerLink = Mark.create({
    name: 'hasSpoilerLink',

    addAttributes() {
        return {
            spoiler: {
                default: null,
                parseHTML: (element) => element.getAttribute('spoiler'),
                renderHTML: (attributes) => {
                    if (!attributes['spoiler']) return {};
                    return { spoiler: attributes['spoiler'] };
                },
            },
        };
    },

    parseHTML() {
        return [{ tag: 'span[spoiler]' }];
    },

    renderHTML({ HTMLAttributes }) {
        return ['span', mergeAttributes(HTMLAttributes, { class: 'has-spoiler-link' }), 0];
    },

    addCommands() {
        return {
            setHasSpoilerLink:
                (attributes) =>
                ({ commands }) => {
                    return commands.setMark(this.name, attributes);
                },
            unsetHasSpoilerLink:
                () =>
                ({ commands }) => {
                    return commands.unsetMark(this.name);
                },
        };
    },
});

export default HasSpoilerLink;
