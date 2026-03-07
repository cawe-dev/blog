const getChangelogIcon = (type: string) => {
    switch (type) {
        case 'feature':
            return 'i-lucide-sparkles';
        case 'fix':
            return 'i-lucide-wrench';
        case 'improvement':
            return 'i-lucide-trending-up';
        default:
            return 'i-lucide-dot';
    }
};

const getChangelogIconColor = (type: string) => {
    switch (type) {
        case 'feature':
            return 'text-[var(--ui-primary)]';
        case 'fix':
            return 'text-[var(--ui-error)]';
        case 'improvement':
            return 'text-[var(--ui-success)]';
        default:
            return 'text-[var(--ui-text-muted)]';
    }
};

export { getChangelogIcon, getChangelogIconColor };
