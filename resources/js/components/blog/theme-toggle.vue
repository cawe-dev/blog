<script setup lang="ts">
import { useContext } from '@/composables/useContext';

const { context, toggleContext } = useContext();
</script>

<template>
    <button
        @click="toggleContext"
        class="relative flex h-9 cursor-pointer items-center gap-1 rounded-full border border-default bg-accented p-1 transition-colors"
        :aria-label="`Alternar para contexto ${context === 'professional' ? 'personal' : 'profissional'}`"
    >
        <span
            :class="[
                'flex h-7 w-full items-center justify-center rounded-full transition-all',
                context === 'professional' ? 'bg-primary p-2 text-inverted' : 'text-muted',
            ]"
        >
            <UIcon name="i-lucide-briefcase" class="mx-0.5 h-4 w-4" /><span v-if="context === 'professional'" class="mr-1 ml-0.5 text-xs"
                >Profissional</span
            >
        </span>
        <span
            :class="[
                'flex h-7 w-full items-center justify-center rounded-full transition-all',
                context === 'personal' ? 'bg-primary p-2 text-inverted' : 'text-muted',
            ]"
        >
            <UIcon name="i-lucide-coffee" class="mx-0.5 h-4 w-4" /><span v-if="context === 'personal'" class="mr-1 ml-0.5 text-xs">Pessoal</span>
        </span>
    </button>
</template>
