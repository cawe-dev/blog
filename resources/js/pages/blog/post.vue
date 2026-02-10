<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import Layout from '@/layouts/blog.vue'
import { Link } from '@inertiajs/vue3'
import type { IPost } from '@/types/models/post'
import type { IViewMode, ViewModeKeys } from '@/types/enums/contentPostViewMode'
import { VIEW_MODE_CONFIG } from '@/types/enums/contentPostViewMode'
import ReferencePopover from '@/components/blog/reference-popover.vue';

const props = defineProps<{
    post: IPost
}>()

defineOptions({ layout: Layout })

const readingProgress = ref<number>(0)
const referencePopoverRef = ref<InstanceType<typeof ReferencePopover>>()
const viewMode = ref<ViewModeKeys>('concept')

function updateReadingProgress() {
    const scrollTop = window.scrollY
    const docHeight = document.documentElement.scrollHeight - window.innerHeight
    readingProgress.value = docHeight > 0 ? Math.min(100, Math.round((scrollTop / docHeight) * 100)) : 0
}

onMounted(() => {
    window.addEventListener('scroll', updateReadingProgress)
    updateReadingProgress()
})

onUnmounted(() => {
    window.removeEventListener('scroll', updateReadingProgress)
})

const formattedDate = computed(() => {
    return new Date(props.post.created_at).toLocaleDateString('pt-BR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
})

const formattedUpdateDate = computed(() => {
    return new Date(props.post.updated_at).toLocaleDateString('pt-BR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
})

const wasEdited = computed(() => {
    const created = new Date(props.post.created_at).getTime()
    const updated = new Date(props.post.updated_at).getTime()
    return updated - created > 3600000
})

function extractTextFromNode(node: any): string {
    if (node.type === 'text' && node.text) {
        return node.text + ' '
    }

    if (node.content && Array.isArray(node.content)) {
        return node.content.map(extractTextFromNode).join('')
    }

    if (node.content && typeof node.content === 'object' && node.content !== null) {
        return extractTextFromNode(node.content)
    }

    return ''
}

const viewModeItems = computed<IViewMode[]>(() => {
    return (Object.keys(props.post.content_html) as ViewModeKeys[]).map(key => ({
        ...VIEW_MODE_CONFIG[key],
        content: props.post.content_html[key]
    }));
});

const estimatedReadTime = computed(() => {
    if (!props.post.content) {
        return 1
    }
    try {
        const contentDoc = typeof props.post.content === 'string' ? JSON.parse(props.post.content) : props.post.content;
        const text = extractTextFromNode(contentDoc);
        const words = text.trim().split(/\s+/).filter(Boolean).length;
        return Math.max(1, Math.ceil(words / 200));
    } catch (e) {
        return 1;
    }
})

const copyLink = async () => {
    await navigator.clipboard.writeText(window.location.href)
}

const shareOnX = () => {
    const url = encodeURIComponent(window.location.href)
    const text = encodeURIComponent(props.post.title)
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank')
}
</script>

<template>
    <div v-if="post" class="min-h-screen bg-default">
        <section name="top-progress-bar">
            <div class="fixed top-0 left-0 z-50 h-1 w-full bg-accented">
                <div class="h-full bg-primary transition-all duration-150" :style="{ width: `${readingProgress}%` }" />
            </div>
        </section>
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6">
            <div class="flex gap-8">
                <article class="min-w-0 flex-1">
                    <header class="mb-8">
                        <Link href="/"
                            class="mb-4 inline-flex items-center gap-1.5 text-sm text-muted transition-colors hover:text-default">
                            <UIcon name="i-lucide-arrow-left" class="h-4 w-4" />
                            Voltar ao Blog
                        </Link>
                        <h1
                            class="mb-4 text-balance text-3xl font-bold tracking-tight text-default sm:text-4xl lg:text-5xl">
                            {{ post.title }}
                        </h1>

                        <section name="metadata-section">
                            <div class="mb-6 flex flex-wrap items-center gap-3 text-sm">
                                <div name="create-date" class="flex items-center gap-1.5 text-muted">
                                    <UIcon name="i-lucide-calendar" class="h-4 w-4" />
                                    <time :datetime="post.created_at">{{ formattedDate }}</time>
                                </div>

                                <UBadge name="edited-post-indicator" v-if="wasEdited" variant="subtle" color="primary"
                                    size="sm">
                                    <UIcon name="i-lucide-pencil" class="mr-1 h-3 w-3" />
                                    Editado em {{ formattedUpdateDate }}
                                </UBadge>

                                <div name="esmative-read-time" class="flex items-center gap-1.5 text-muted">
                                    <UIcon name="i-lucide-clock" class="h-4 w-4" />
                                    <span>{{ estimatedReadTime }} min de leitura</span>
                                </div>

                                <div name="category-post" class="flex items-center gap-2">
                                    <UBadge :key="post.category.id" variant="subtle" color="primary">
                                        {{ post.category.name }}
                                    </UBadge>
                                </div>
                            </div>
                        </section>
                    </header>

                    <section name="content-section">
                        <div class="relative">
                            <div v-if="typeof post.content_html === 'string'" class="prose dark:prose-invert max-w-none [&>p>span[data-reference-id]]:text-(--ui-primary)
                                [&>p>span[data-reference-id]]:font-bold
                                [&>p>span[data-reference-id]]:bg-primary/10 [&>p>span[data-reference-id]]:cursor-help"
                                v-html="post.content_html" @mouseover="referencePopoverRef?.handleMouseOver($event)" />

                            <div v-else class="sticky top-2 z-40 -mx-4 mb-8 px-4 py-3 sm:mx-0 sm:px-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-2">
                                        <UIcon name="i-lucide-layers" class="h-4 w-4 text-muted" />
                                        <span class="text-sm font-medium text-default">Modo de Visualização</span>
                                    </div>
                                </div>
                                <UTabs v-model="viewMode" :items="viewModeItems" size="sm" class="mt-4">
                                    <template #content="{ item }">
                                        <div class="mt-4">
                                            <h3 class="mb-2 text-sm font-bold uppercase text-muted">
                                                {{ item.label }}
                                            </h3>
                                            <div class="prose dark:prose-invert max-w-none [&>p>span[data-reference-id]]:text-(--ui-primary)
                                [&>p>span[data-reference-id]]:font-bold
                                [&>p>span[data-reference-id]]:bg-primary/10 [&>p>span[data-reference-id]]:cursor-help"
                                                v-html="item.content"
                                                @mouseover="referencePopoverRef?.handleMouseOver($event)" />
                                        </div>
                                    </template>
                                </UTabs>
                            </div>

                            <ReferencePopover ref="referencePopoverRef" :references="post.references" />
                        </div>
                    </section>

                    <USeparator class="my-8" />

                    <footer class="space-y-8">
                        <section name="tags-section">
                            <div>
                                <h3 class="mb-3 text-sm font-semibold text-default">Tags</h3>
                                <div class="flex flex-wrap gap-2">
                                    <UBadge v-for="tag in post.tags" :key="tag.id" variant="soft" color="neutral"
                                        size="sm">
                                        #{{ tag.name }}
                                    </UBadge>
                                </div>
                            </div>
                        </section>

                        <section name="share-section">
                            <div>
                                <h3 class="mb-3 text-sm font-semibold text-default">Compartilhar </h3>
                                <div class="flex gap-2">
                                    <UButton variant="outline" color="neutral" size="sm" icon="i-lucide-link"
                                        @click="copyLink">
                                        Copiar Link
                                    </UButton>
                                    <UButton variant="outline" color="neutral" size="sm" icon="i-lucide-twitter"
                                        @click="shareOnX">
                                        Compartilhar no X
                                    </UButton>
                                </div>
                            </div>
                        </section>

                        <USeparator />
                    </footer>
                </article>

                <aside class="hidden w-64 shrink-0 lg:block">
                    <div class="sticky top-16 space-y-6">
                        <UCard name="reading-progress-widget">
                            <template #header>
                                <div class="flex items-center gap-2">
                                    <UIcon name="i-lucide-book-open" class="h-4 w-4 text-primary" />
                                    <span class="text-sm font-medium text-default">Progresso</span>
                                </div>
                            </template>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-muted">Leitura:</span>
                                </div>
                                <UProgress v-model="readingProgress" status :max="100" size="xs" />
                            </div>
                        </UCard>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    <section v-else name="loading-post-section">
        <div class="flex items-center justify-center min-h-screen">
            <USkeleton class="w-48 h-8 mb-4" />
        </div>
    </section>
</template>

<style scoped>
.prose :deep(h2) {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: var(--ui-text);
}

.prose :deep(h3) {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: var(--ui-text);
}

.prose :deep(p) {
    margin-bottom: 1rem;
    line-height: 1.75;
}

.prose :deep(pre) {
    background: var(--ui-bg-inverted);
    color: var(--ui-text-inverted);
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.prose :deep(code) {
    font-family: var(--font-mono, monospace);
    font-size: 0.875em;
}

.prose :deep(p code) {
    background: var(--ui-bg-accented);
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    color: var(--ui-primary);
}

.prose :deep(strong) {
    font-weight: 600;
    color: var(--ui-text-highlighted);
}

.prose :deep(em) {
    font-style: italic;
}

.prose :deep(.reference-term) {
    border-bottom: 1px dashed var(--ui-primary);
    cursor: help;
    color: white;
}

.prose :deep(p:has(img)) {
    text-align: center;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: var(--ui-text-muted);
    margin-top: 0.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.prose :deep(iframe) {
    display: block;
    width: 100%;
    aspect-ratio: 16 / 9;
    margin-inline: auto;
    border-radius: 0.5rem;
}

.prose :deep(img) {
    max-width: 100%;
    height: auto;
    display: inline-block;
    border-radius: 0.5rem;
}
</style>