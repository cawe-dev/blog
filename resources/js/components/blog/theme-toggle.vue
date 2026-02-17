<script setup lang="ts">
import { useContext } from '@/composables/useContext'

const { context, toggleContext } = useContext()
</script>

<template>
    <button @click="toggleContext"
        class="relative flex h-9 items-center gap-1 rounded-full border border-default bg-accented p-1 transition-colors"
        :aria-label="`Alternar para contexto ${context === 'professional' ? 'personal' : 'profissional'}`">
        <span :class="[
            'flex h-7 w-full items-center justify-center rounded-full transition-all',
            context === 'professional'
                ? 'bg-primary text-inverted'
                : 'text-muted'
        ]">
            <UIcon name="i-lucide-briefcase" class="h-4 w-4 mx-0.5" /><span v-if="context === 'professional'"
                class="ml-0.5 mr-1 text-xs">profissional</span>
        </span>
        <span :class="[
            'flex h-7 w-full items-center justify-center rounded-full transition-all',
            context === 'personal'
                ? 'bg-primary text-inverted'
                : 'text-muted'
        ]">
            <UIcon name="i-lucide-coffee" class="h-4 w-4 mx-0.5" /><span v-if="context === 'personal'"
                class="ml-0.5 mr-1 text-xs">pessoal</span>
        </span>
    </button>
</template>