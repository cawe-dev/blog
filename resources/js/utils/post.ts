function extractTextFromNode(node: any): string {
    if (node.type === 'text' && node.text) {
        return node.text + ' ';
    }

    if (node.content && Array.isArray(node.content)) {
        return node.content.map(extractTextFromNode).join('');
    }

    if (node.content && typeof node.content === 'object' && node.content !== null) {
        return extractTextFromNode(node.content);
    }

    return '';
}

export default extractTextFromNode;
