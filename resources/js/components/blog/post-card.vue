<script lang="ts" setup>
import { computed, ref } from 'vue';
import type { IPost } from '@/types/models/post';
import { formatDate } from '@/utils/date';
import CategoryBadge from './category-badge.vue';

const props = defineProps<{
    post: IPost;
    viewMode?: 'grid' | 'list';
}>();

const isSpoilerRevealed = ref(false);

const computedPath = computed(() => `/blog/post/${props.post.slug}`);

const formattedDate = computed(() => {
    return formatDate(props.post.created_at);
});

const toggleSpoiler = () => {
    isSpoilerRevealed.value = !isSpoilerRevealed.value;
};
</script>

<template>
    <UBlogPost
        class="h-full"
        :to="computedPath"
        :orientation="viewMode === 'grid' ? 'vertical' : 'horizontal'"
        :ui="{
            title: 'text-xl font-bold text-default mb-2 group-hover:text-primary transition-colors',
            description: 'text-muted text-base',
            date: 'text-xs text-muted mt-4',
            authors: 'hidden',
        }"
    >
        <template v-if="post.thumbnail" #header>
            <img
                :src="post.thumbnail"
                :alt="post.title"
                class="h-full w-full object-cover object-center transition-transform duration-300 group-hover/blog-post:scale-105"
            />

            <div
                name="gradient-overlay"
                class="pointer-events-none absolute inset-0 bg-linear-to-b from-black/40 via-transparent to-transparent"
                aria-hidden="true"
            />

            <div name="left-badges-overlay" class="absolute top-3 left-3 z-10 flex flex-wrap gap-1.5 font-mono">
                <UBadge :label="post.type_label" color="primary" variant="solid" />

                <UBadge v-if="post.has_spoiler" color="warning" variant="solid" size="sm" icon="i-lucide-alert-triangle" label="Spoiler" />
            </div>

            <div name="right-badges-overlay" class="absolute top-3 right-3 z-10">
                <UBadge color="neutral" variant="solid" size="sm" icon="i-lucide-clock" :label="`${post.estimated_read_time} min de leitura`" />
            </div>
        </template>
        <template #badge>
            <UBadge v-if="post.pinned_at" color="secundary" variant="subtle">
                <UIcon name="i-lucide-pin" class="h-4 w-4 text-toned" />
            </UBadge>

            <template v-for="changeLog in post.change_logs" :key="changeLog.id">
                <UBadge
                    v-if="post.change_logs"
                    :label="changeLog.type"
                    class="bg-(--brand-secondary) font-mono font-semibold ring-1 ring-(--ui-foreground) ring-inset"
                />
            </template>

            <CategoryBadge :category="post.category" :variant="solid" :color="primary" />
        </template>

        <template #date>
            <div class="flex items-center justify-between pb-4">
                <time :datetime="post.created_at" class="flex items-center gap-1 text-xs font-medium text-muted">
                    <UIcon name="i-heroicons-calendar" class="h-3.5 w-3.5" />
                    {{ formattedDate }}
                </time>
            </div>
        </template>

        <template #title>
            <div class="mb-2 flex flex-wrap items-center gap-2">
                <h1 class="line-clamp-2 text-xl font-bold text-default transition-colors group-hover:text-primary">
                    {{ post.title }}
                </h1>
            </div>
        </template>

        <template #description>
            <div class="cursor-pointer" @click.prevent="post.has_spoiler && toggleSpoiler()">
                <span :class="post.has_spoiler && !isSpoilerRevealed ? 'my-10 blur-sm select-none' : ''">
                    <p class="line-clamp-3 text-base leading-relaxed text-balance text-muted">{{ post.excerpt }}</p>
                </span>

                <span
                    v-if="post.has_spoiler && !isSpoilerRevealed"
                    class="absolute"
                    :class="
                        viewMode === 'list'
                            ? 'inset-x-1/4 inset-y-12/16 sm:inset-x-6/9 sm:inset-y-12/22'
                            : 'inset-x-1/4 inset-y-9/12 sm:inset-x-1/3 sm:inset-y-12/15'
                    "
                >
                    <UBadge label="Esse post contém spoiler(s)" color="neutral" variant="solid" icon="i-heroicons-eye-slash" />
                </span>
            </div>

            <section name="tags-grid-mode">
                <div class="hidden sm:block" v-if="viewMode === 'list'">
                    <div class="absolute bottom-2" v-if="post.tags && post.tags.length">
                        <UBadge v-for="tag in post.tags.slice(0, 2)" :key="tag.id" :label="`#${tag.name}`" variant="soft" size="md" class="mx-0.5" />
                        <span v-if="post.tags.length > 2" class="self-center text-xs text-muted"> +{{ post.tags.length - 2 }} </span>
                    </div>
                </div>
            </section>
        </template>

        <template #footer>
            <section :class="viewMode === 'list' ? 'sm:hidden' : ''" name="tags-list-mode">
                <div class="absolute bottom-2 ml-0.5" v-if="post.tags && post.tags.length">
                    <UBadge v-for="tag in post.tags.slice(0, 2)" :key="tag.id" :label="`#${tag.name}`" variant="soft" size="md" class="ml-1" />
                    <span v-if="post.tags.length > 2" class="self-center text-xs text-muted"> +{{ post.tags.length - 2 }} </span>
                </div>
            </section>

            <div :class="viewMode === 'list' ? 'sm:hidden' : ''" class="flex w-full items-center justify-between pt-4">
                <div class="flex-1"></div>
                <UButton
                    size="md"
                    class="group transform bg-transparent px-4 py-2 transition-all duration-200 hover:scale-105 hover:bg-transparent active:scale-95"
                    aria-label="Ler mais"
                >
                    <span class="flex items-center gap-2">
                        <span class="font-medium text-muted">ler mais</span>
                        <UIcon name="i-heroicons-arrow-right" class="h-4 w-4 text-primary" />
                    </span>
                </UButton>
            </div>
        </template>
    </UBlogPost>
</template>
