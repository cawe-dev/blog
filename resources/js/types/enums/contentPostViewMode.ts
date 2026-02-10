export interface IViewMode {
    label: string;
    icon: string;
    value: string;
    content?: string;
}

export type ViewModeKeys = 'concept' | 'technical';

export const VIEW_MODE_CONFIG: Record<ViewModeKeys, Omit<IViewMode, 'content'>> = {
    concept: {
        label: 'Conceito Universal',
        icon: 'i-lucide-lightbulb',
        value: 'concept',
    },
    technical: {
        label: 'Implementação Técnica',
        icon: 'i-lucide-code',
        value: 'technical',
    },
};