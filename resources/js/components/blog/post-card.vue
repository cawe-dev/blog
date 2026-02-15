<script lang="ts" setup>
import type { IPost } from '@/types/models/post'
import { computed, ref } from 'vue'

const props = defineProps<{
    post: IPost,
    viewMode?: 'grid' | 'list'
}>()

const isSpoilerRevealed = ref(false)

const computedPath = computed(() => `/blog/post/${props.post.slug}`)

const formattedDate = computed(() => {
    return new Date(props.post.created_at).toLocaleDateString('pt-BR', {
        day: '2-digit', month: 'long', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    })
})

const toggleSpoiler = () => {
    isSpoilerRevealed.value = !isSpoilerRevealed.value
}
</script>

<template>
    <UBlogPosts :orientation="viewMode === 'list' ? 'vertical' : 'vertical'">
        <UBlogPost :to="computedPath" :image="post.thumbnail" :class="viewMode === 'list' ? 'px-4' : ''" :ui="{
            image: 'object-cover',
            title: 'text-xl font-bold text-default mb-2 group-hover:text-primary transition-colors',
            description: 'text-muted text-base',
            date: 'text-xs text-muted mt-4',
            authors: 'hidden',
        }">
            <template #badge>
                <UBadge v-if="post.pinned_at" color="secundary" variant="subtle">
                    <UIcon name="i-lucide-pin" class="w-4 h-4 text-toned" />
                </UBadge>
                <UBadge :label="post.type_label" color="primary" variant="solid" />
                <UBadge v-if="post.change_logs" v-for="changeLog in post.change_logs" :key="changeLog.id"
                    :label="changeLog.type"
                    class="bg-(--brand-secondary) ring-1 ring-inset ring-(--ui-foreground) font-mono font-semibold" />
                <UBadge :key="post.category.id" variant="subtle" color="primary">
                    {{ post.category.name }}
                </UBadge>
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
                <div class="flex items-center gap-2 flex-wrap mb-2">
                    <h3 class=" text-xl font-bold text-default group-hover:text-primary transition-colors line-clamp-2">
                        {{ post.title }}
                        <span v-if="post.has_spoiler" class="text-xs text-accented">

                            <UBadge label="Alerta de Spoiler" color="warning" variant="subtle"
                                icon="i-heroicons-exclamation-triangle" />
                        </span>
                    </h3>
                    <UBadge v-for="category in post.categories" :key="category.id" :label="category.name"
                        variant="subtle" size="xs" />
                </div>
            </template>

            <template #description>
                <span @click.prevent="post.has_spoiler && toggleSpoiler()">

                    <span :class="[
                        'block text-base leading-relaxed text-muted line-clamp-3',
                        post.has_spoiler && !isSpoilerRevealed ? 'blur-sm select-none my-10' : ''
                    ]">
                        {{ post.excerpt }}
                    </span>

                    <span v-if="post.has_spoiler && !isSpoilerRevealed"
                        class="absolute inset-0 flex items-center justify-center hover:scale-105 transition-transform">
                        <UBadge label="Esse post contém spoiler(s)" color="neutral" variant="solid"
                            icon="i-heroicons-eye-slash" />
                    </span>
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
                            <UIcon name="i-heroicons-arrow-right" class="w-4 h-4 text-primary" />
                        </span>
                    </UButton>
                </div>
            </template>
        </UBlogPost>
    </UBlogPosts>
</template>