<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue'
import type { IChangelogs, IChangelogItem } from '@/types/models/changeLog'
import { formatRelativeTime } from '@/utils/date';
import BellNotification from './bell-notification.vue'
import GlobalSearch from './global-search.vue';
import Logo from './logo.vue';
import ThemeToggle from './theme-toggle.vue';

const searchOpen = ref(false)
const searchQuery = ref('')
const page = usePage();

const changelogs = computed(() => page.props.notifications.changelogs as IChangelogs);

const changelogItems = computed((): IChangelogItem[] =>
    changelogs.value.map(item => ({
        title: item.title,
        slot: 'changelog-item' as const,
        type: item.type,
        version: item.version,
        date: item.published_at,
        formattedDate: formatRelativeTime(item.published_at),
    }))
)

</script>

<template>
    <header
        class="sticky top-0 z-50 w-full border-b border-default bg-(--ui-bg)/95 backdrop-blur supports-backdrop-filter:bg-(--ui-bg)/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6">
            <Logo />
            <div class="flex items-center gap-2">
                <GlobalSearch v-model:searchOpen="searchOpen" v-model:searchQuery="searchQuery" />
                <BellNotification :items="changelogItems" />
                <ThemeToggle />
            </div>
        </div>
    </header>
</template>
