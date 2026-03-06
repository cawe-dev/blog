<script lang="ts" setup>
import type { IPost } from '@/types/models/post'
import { computed, ref } from 'vue'
import { formatDate } from '@/utils/date'
import CategoryBadge from './category-badge.vue';

const props = defineProps<{
    post: IPost,
    viewMode?: 'grid' | 'list'
}>()

const isSpoilerRevealed = ref(false)

const computedPath = computed(() => `/blog/post/${props.post.slug}`)

const formattedDate = computed(() => {
    return formatDate(props.post.created_at)
})

const toggleSpoiler = () => {
    isSpoilerRevealed.value = !isSpoilerRevealed.value
}
</script>

<template>
    <UBlogPost class="h-full" :to="computedPath" :orientation="viewMode === 'grid' ? 'vertical' : 'horizontal'" :ui="{
        title: 'text-xl font-bold text-default mb-2 group-hover:text-primary transition-colors',
        description: 'text-muted text-base',
        date: 'text-xs text-muted mt-4',
        authors: 'hidden',
    }">
        <template v-if="post.thumbnail" #header>
            <img :src="post.thumbnail" :alt="post.title"
                class="object-cover object-center w-full h-full transition-transform duration-300 group-hover/blog-post:scale-105" />

            <div name="gradient-overlay"
                class="absolute inset-0 bg-linear-to-b from-black/40 via-transparent to-transparent pointer-events-none"
                aria-hidden="true" />

            <div name="left-badges-overlay" class="absolute top-3 left-3 z-10 flex flex-wrap gap-1.5 font-mono">
                <UBadge :label="post.type_label" color="primary" variant="solid" />

                <UBadge v-if="post.has_spoiler" color="warning" variant="solid" size="sm" icon="i-lucide-alert-triangle"
                    label="Spoiler" />
            </div>

            <div name="right-badges-overlay" class="absolute top-3 right-3 z-10">
                <UBadge color="neutral" variant="solid" size="sm" icon="i-lucide-clock"
                    :label="`${post.estimated_read_time} min de leitura`" />
            </div>
        </template>
        <template #badge>
            <UBadge v-if="post.pinned_at" color="secundary" variant="subtle">
                <UIcon name="i-lucide-pin" class="w-4 h-4 text-toned" />
            </UBadge>
            <UBadge v-if="post.change_logs" v-for="changeLog in post.change_logs" :key="changeLog.id"
                :label="changeLog.type"
                class="bg-(--brand-secondary) ring-1 ring-inset ring-(--ui-foreground)  font-mono font-semibold" />
            <CategoryBadge :category="post.category" :variant="solid" :color="primary" />
        </template>

        <template #date>
            <div class="flex items-center justify-between pb-4">
                <time :datetime="post.created_at" class="text-xs font-medium text-muted flex items-center gap-1">
                    <UIcon name="i-heroicons-calendar" class="w-3.5 h-3.5" />
                    {{ formattedDate }}
                </time>
            </div>
        </template>

        <template #title>
            <div class="flex items-center gap-2 flex-wrap mb-2">
                <h1 class=" text-xl font-bold text-default group-hover:text-primary transition-colors line-clamp-2">
                    {{ post.title }}
                </h1>
            </div>
        </template>

        <template #description>
            <div class="cursor-pointer" @click.prevent="post.has_spoiler && toggleSpoiler()">

                <span :class="post.has_spoiler && !isSpoilerRevealed ? 'blur-sm select-none my-10' : ''
                    ">
                    <p class="text-base leading-relaxed text-muted text-balance line-clamp-3">{{ post.excerpt }}</p>
                </span>

                <span v-if="post.has_spoiler && !isSpoilerRevealed" class="absolute"
                    :class="viewMode === 'list' ? 'inset-y-12/16 inset-x-1/4 sm:inset-y-12/22 sm:inset-x-6/9' : 'inset-y-9/12 inset-x-1/4 sm:inset-y-12/15 sm:inset-x-1/3'">
                    <UBadge label="Esse post contém spoiler(s)" color="neutral" variant="solid"
                        icon="i-heroicons-eye-slash" />
                </span>
            </div>

            <section name="tags-grid-mode">
                <div class="hidden sm:block" v-if="viewMode === 'list'">
                    <div class="absolute bottom-2" v-if="post.tags && post.tags.length">
                        <UBadge v-for="tag in post.tags.slice(0, 2)" :key="tag.id" :label="`#${tag.name}`"
                            variant="soft" size="md" class="mx-0.5" />
                        <span v-if="post.tags.length > 2" class="text-xs text-muted self-center">
                            +{{ post.tags.length - 2 }}
                        </span>
                    </div>
                </div>
            </section>
        </template>

        <template #footer>
            <section :class="viewMode === 'list' ? 'sm:hidden' : ''" name="tags-list-mode">
                <div class="absolute ml-0.5 bottom-2" v-if="post.tags && post.tags.length">
                    <UBadge v-for="tag in post.tags.slice(0, 2)" :key="tag.id" :label="`#${tag.name}`" variant="soft"
                        size="md" class="ml-1" />
                    <span v-if="post.tags.length > 2" class="text-xs text-muted self-center">
                        +{{ post.tags.length - 2 }}
                    </span>
                </div>
            </section>

            <div :class="viewMode === 'list' ? 'sm:hidden' : ''" class=" flex items-center justify-between w-full pt-4">
                <div class="flex-1"></div>
                <UButton size="md"
                    class="group transform transition-all duration-200 bg-transparent hover:bg-transparent hover:scale-105 active:scale-95 px-4 py-2"
                    aria-label="Ler mais">
                    <span class="flex items-center gap-2">
                        <span class="font-medium text-muted">ler mais</span>
                        <UIcon name="i-heroicons-arrow-right" class="w-4 h-4 text-primary" />
                    </span>
                </UButton>
            </div>
        </template>
    </UBlogPost>
</template>