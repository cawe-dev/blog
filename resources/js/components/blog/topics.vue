<script setup lang="ts">
import { computed } from 'vue';
import type { IPost } from '@/types/models/post';

const props = defineProps<{
    subTopics: IPost['sub_topics'];
}>();

const formattedSubTopics = computed(() => {
    return props.subTopics?.map((topic) => {
        return topic.charAt(0).toUpperCase() + topic.replace(/-/g, ' ').slice(1);
    });
});
</script>

<template>
    <UCard v-if="subTopics && subTopics.length > 0" name="subtopics-widget">
        <template #header>
            <div class="flex items-center gap-2">
                <UIcon name="i-lucide-list" class="h-4 w-4 text-primary" />
                <span class="text-sm font-medium text-default">Índice</span>
            </div>
        </template>
        <nav>
            <ul class="space-y-1.5 text-sm">
                <li v-for="(topic, index) in formattedSubTopics" :key="index">
                    <a
                        :href="`#${topic}`"
                        class="block border-l-2 border-transparent px-2.5 py-1.5 text-muted transition-colors duration-150 hover:border-primary hover:bg-accented hover:text-default"
                    >
                        {{ topic }}
                    </a>
                </li>
            </ul>
        </nav>
    </UCard>
</template>
