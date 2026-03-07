<script setup lang="ts">
import { ref } from 'vue';
import type { PropType } from 'vue';
import type { IChangelogItem } from '@/types/models/changeLog';
import { getChangelogIcon, getChangelogIconColor } from '@/utils/changelog';
import { formatDate } from '@/utils/date';

const props = defineProps({
    features: {
        type: Array as PropType<IChangelogItem[]>,
    },
});
const isOpen = ref(false);
</script>

<template>
    <div v-if="props.features">
        <UBadge color="primary" variant="outline" @click="isOpen = true" class="cursor-pointer hover:bg-accented">
            <UIcon name="i-lucide-sparkles" class="mr-1" />
            Novidades Pós-Publicação
        </UBadge>

        <UModal v-model:open="isOpen">
            <template #content>
                <UCard>
                    <template #header>
                        <div class="flex items-center">
                            <h2 class="text-lg font-semibold">Funcionalidades pós-publicação</h2>
                        </div>
                    </template>

                    <ul>
                        <li v-for="feature in features" :key="feature.id" class="p-4">
                            <div class="flex justify-between">
                                <p class="truncate font-semibold">
                                    <UIcon :name="getChangelogIcon(feature.type)" :class="['inline', getChangelogIconColor(feature.type)]" />
                                    {{ feature.title }}
                                </p>
                                <UBadge :label="`v${feature.version}`" color="primary" variant="subtle" />
                            </div>
                            <p class="mt-1 text-sm text-muted">
                                <UIcon name="i-lucide-calendar-check" class="mr-1 mb-1.5 inline" />
                                Lançado em: {{ formatDate(feature.published_at) }}
                            </p>
                        </li>
                    </ul>

                    <template #footer>
                        <div class="flex justify-end">
                            <UButton color="primary" @click="isOpen = false">Fechar</UButton>
                        </div>
                    </template>
                </UCard>
            </template>
        </UModal>
    </div>
</template>
