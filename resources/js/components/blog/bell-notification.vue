<script setup lang="ts">

interface ChangelogItem {
    label: string
    date: Date
    type: string
}

defineProps<{
    items: ChangelogItem[]
}>()

const getChangelogIcon = (type: string) => {
    switch (type) {
        case 'feature': return 'i-lucide-zap'
        case 'fix': return 'i-lucide-bug'
        case 'improvement': return 'i-lucide-check-circle-2'
        default: return 'i-lucide-circle'
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
    <UDropdownMenu :items="items">
        <UButton variant="ghost" size="sm" class="relative" square>
            <UIcon name="i-lucide-bell" class="h-4 w-4" />
            <span
                class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] font-medium text-inverted">
                {{ items.length }}
            </span>
            <span class="sr-only">Novidades</span>
        </UButton>

        <template #changelog-item="{ item }">
            <div class="flex flex-col items-start gap-1 py-1">
                <div class="flex items-center gap-2">
                    <UIcon :name="getChangelogIcon(item.type)"
                        :class="['h-3.5 w-3.5', getChangelogIconColor(item.type)]" />
                    <span class="text-sm font-medium text-default">{{ item.label }}</span>
                </div>
                <span class="text-xs text-muted">{{ item.date }}</span>
            </div>
        </template>
    </UDropdownMenu>
</template>