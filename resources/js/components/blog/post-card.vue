<script lang="ts" setup>
import type { IPost } from '@/types/models/post'
import { computed, ref } from 'vue'

const props = defineProps<{
    post: IPost,
    viewMode?: 'grid' | 'list'
}>()

const computedPath = computed(() => `/blog/post/${props.post.id}`)

const formattedDate = computed(() => {
    return new Date(props.post.created_at).toLocaleDateString('pt-BR', {
        day: '2-digit', month: 'long', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    })
})
</script>

<template>
    <UBlogPosts :orientation="viewMode === 'list' ? 'vertical' : 'vertical'">
        <UBlogPost :to="computedPath" :class="viewMode === 'list' ? 'px-4' : ''" :ui="{
            title: 'text-xl font-bold text-default mb-2 group-hover:text-primary transition-colors',
            description: 'text-muted text-base',
            date: 'text-xs text-muted mt-4',
            authors: 'hidden',
        }">
            <template #badge>
                <UBadge :label="post.type_label" color="primary" variant="solid" />
            </template>

            <template #date>
                <div class="flex items-center justify-between pb-4">
                    <time :datetime="post.created_at" class="text-xs font-medium text-muted flex items-center gap-1">
                        <UIcon name="i-heroicons-calendar" class="w-3.5 h-3.5" />
                        {{ formattedDate }}
                    </time>
                    <div class="w-px h-4 bg-primary mx-2"></div>

                    <div class="flex gap-1.5" v-if="post.tags && post.tags.length">
                        <UBadge v-for="tag in post.tags.slice(0, 2)" :key="tag.id" :label="`#${tag.name}`"
                            variant="soft" size="xs" />
                        <span v-if="post.tags.length > 2" class="text-xs text-muted self-center">
                            +{{ post.tags.length - 2 }}
                        </span>
                    </div>
                </div>
            </template>

            <template #title>
                <div class="flex items-center gap-2 flex-wrap mb-2"">
                <h3 class=" text-xl font-bold text-default group-hover:text-primary transition-colors line-clamp-2">
                    {{ post.title }}
                    </h3>
                    <UBadge v-for="category in post.categories" :key="category.id" :label="category.name"
                        variant="subtle" size="xs" />
                </div>
            </template>

            <template #description>
                <span class='block text-base leading-relaxed text-muted line-clamp-3'">
                        {{ post.excerpt }}
                    </span>
            </template>

<template #footer>
                <div class=" flex items-center justify-between w-full pt-4">
                    <div class="flex-1"></div>
                    <UButton size="md"
                        class="group transform transition-all duration-200 bg-transparent hover:bg-transparent hover:scale-105 active:scale-95 px-4 py-2"
                        aria-label="Ler mais">
                        <span class="flex items-center gap-2">
                            <span class="font-medium text-muted">ler mais</span>
                            <UIcon name="i-heroicons-arrow-right"
                                class="w-4 h-4 transform transition-transform duration-200 group-hover:translate-x-1 text-primary" />
                        </span>
                    </UButton>
                    </div>
            </template>
        </UBlogPost>
    </UBlogPosts>
</template>