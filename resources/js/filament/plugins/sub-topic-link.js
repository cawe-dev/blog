import { Mark, mergeAttributes } from '@tiptap/core';

const subTopicLink = Mark.create({
    name: 'subTopicLink',

    addAttributes() {
        return {
            'subTopicId': {
                default: null,
                parseHTML: element => element.getAttribute('subTopicId'),
                renderHTML: attributes => {
                    if (!attributes['subTopicId']) return {};
                    return { 'subTopicId': attributes['subTopicId'] };
                },
            },
        };
    },

    parseHTML() {
        return [{ tag: 'h2[id]' }];
    },

    renderHTML({ HTMLAttributes }) {
        return ['h2', mergeAttributes(HTMLAttributes, { class: 'sub-topic-link' }), 0];
    },

    addCommands() {
        return {
            setSubTopicLink: (attributes) => ({ commands }) => {
                return commands.setMark(this.name, attributes);
            },
            unsetSubTopicLink: () => ({ commands }) => {
                return commands.unsetMark(this.name);
            },
        };
    },
});

export default subTopicLink;