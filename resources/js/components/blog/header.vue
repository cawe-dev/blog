<script setup lang="ts">
import { ref, computed } from 'vue'
import BellNotification from './bell-notification.vue'
import Logo from './logo.vue';
import Navbar from './navbar.vue';
import GlobalSearch from './global-search.vue';
import ThemeToggle from './theme-toggle.vue';


const searchOpen = ref(false)
const searchQuery = ref('')

const changelog = [
    { id: 1, type: 'feature' as const, title: 'Placeholder teste 1', date: 'Há 2 dias' },
    { id: 2, type: 'fix' as const, title: 'Placeholder teste 2', date: 'Há 5 dias' },
    { id: 3, type: 'improvement' as const, title: 'Placeholder teste 3', date: 'Há 1 semana' },
]

const changelogItems = computed(() =>
    changelog.map(item => ({
        label: item.title,
        slot: 'changelog-item' as const,
        type: item.type,
        date: item.date,
    }))
)
</script>

<template>
    <header
        class="sticky top-0 z-50 w-full border-b border-default bg-(--ui-bg)/95 backdrop-blur supports-backdrop-filter:bg-(--ui-bg)/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6">
            <Logo />
            <Navbar />

            <div class="flex items-center gap-2">
                <GlobalSearch v-model:searchOpen="searchOpen" v-model:searchQuery="searchQuery" />
                <BellNotification :items="changelogItems" />
                <ThemeToggle />
            </div>
        </div>
    </header>
</template>
