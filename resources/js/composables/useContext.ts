import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

export const useContext = () => {
    const page = usePage();

    const context = computed(() => page.props.context as 'professional' | 'personal');

    const setContext = (mode: 'professional' | 'personal') => {
        router.post('/set-context', { mode }, {
            preserveScroll: true,
            onSuccess: () => {
                document.documentElement.setAttribute('data-context', mode);
            }
        });
    };

    const logoUrl = computed(() =>
        context.value === 'professional' ? '/images/blog-profissional-logo.svg' : '/images/blog-personal-logo.svg'
    );

    const font = computed(() =>
        context.value === 'professional' ? 'font-professional text-[1.04rem] sm:text-[1.15rem]' : 'font-personal  text-[1.04rem] sm:text-[1.15rem]'
    );
    
    const lineLength = computed(() =>
        context.value === 'professional' ? 'max-w-3xl' : 'max-w-prose'
    );

    const toggleContext = () => {
        setContext(context.value === 'professional' ? 'personal' : 'professional');
    };

    return { context, logoUrl, font, lineLength, setContext, toggleContext };
};
