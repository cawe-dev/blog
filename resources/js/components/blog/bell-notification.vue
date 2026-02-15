<script setup lang="ts">
import { computed } from 'vue'
import type { IChangelogItem } from '@/types/models/changeLog'

const props = defineProps<{
    items: IChangelogItem[]
}>()

const groupedItems = computed(() => {
    const groups: { [version: string]: IChangelogItem[] } = {}

    for (const item of props.items) {
        if (!groups[item.version])
            groups[item.version] = []
        groups[item.version].push(item)
    }

    const groupsKeys = Object.keys(groups)

    const result: any[] = []
    for (const version of groupsKeys) {
        result.push({
            label: `Versão ${version}`,
            version,
            slot: 'version-header',
            disabled: true,
        })

        const logs = groups[version].map(log => ({
            ...log,
            slot: 'changelog-item',
        }))
        result.push(...logs)
    }

    return result
})

const getChangelogIcon = (type: string) => {
    switch (type) {
        case 'feature': return 'i-lucide-sparkles'
        case 'fix': return 'i-lucide-wrench'
        case 'improvement': return 'i-lucide-trending-up'
        default: return 'i-lucide-dot'
    }
}

const getChangelogIconColor = (type: string) => {
    switch (type) {
        case 'feature': return 'text-[var(--ui-primary)]'
        case 'fix': return 'text-[var(--ui-error)]'
        case 'improvement': return 'text-[var(--ui-success)]'
        default: return 'text-[var(--ui-text-muted)]'
    }
}
</script>

<template>
    <UDropdownMenu :items="groupedItems">
        <UButton variant="ghost" size="sm" class="relative" square>
            <UIcon name="i-lucide-bell" class="h-4 w-4" />
            <span
                class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] font-medium text-inverted">
                {{ items.length }}
            </span>
            <span class="sr-only">Novidades</span>
        </UButton>

        <template #version-header="{ item }">
            <div class="py-1">
                <p>Versão <span class="font-bold">{{ item.version }}</span></p>
            </div>
        </template>

        <template #changelog-item="{ item }">
            <div class="flex flex-col items-start gap-1 py-1">
                <div class="flex items-center gap-2">
                    <UIcon :name="getChangelogIcon(item.type)"
                        :class="['h-3.5 w-3.5', getChangelogIconColor(item.type)]" />
                    <span class="text-sm font-medium text-default">{{ item.label }}</span>
                </div>
                <span class="text-xs text-muted">{{ item.formattedDate }}</span>
            </div>
        </template>
    </UDropdownMenu>
</template>