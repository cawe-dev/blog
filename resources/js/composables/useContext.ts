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

    const toggleContext = () => {
        setContext(context.value === 'professional' ? 'personal' : 'professional');
    };

    return { context, setContext, toggleContext };
};
