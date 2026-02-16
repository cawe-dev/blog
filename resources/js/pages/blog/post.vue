<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import Layout from '@/layouts/blog.vue'
import { Link } from '@inertiajs/vue3'
import type { IPost } from '@/types/models/post'
import type { IViewMode, ViewModeKeys } from '@/types/enums/contentPostViewMode'
import { VIEW_MODE_CONFIG } from '@/types/enums/contentPostViewMode'
import ReferencePopover from '@/components/blog/reference-popover.vue';
import { IChangelogs, IChangelogItem } from '@/types/models/changeLog'
import PostFeaturesBagde from '@/components/blog/PostFeaturesBagde.vue'

const props = defineProps<{
    post: IPost,
    changelogs: IChangelogs
}>()

defineOptions({ layout: Layout })

const toast = useToast()

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

    document.querySelectorAll('span[data-has-spoiler="true"]').forEach(el => {
        el.addEventListener('click', () => {
            el.classList.toggle('spoiler-revealed')
        })
    })
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

const viewModeItems = computed<IViewMode[]>(() => {
    return (Object.keys(props.post.content_html).sort() as ViewModeKeys[]).map(key => ({
        ...VIEW_MODE_CONFIG[key],
        content: props.post.content_html[key]
    }));
});

const featuresAfterPost = computed(() => {
    return props.changelogs.filter((changelog: IChangelogItem) => {
        return new Date(changelog.published_at) >= new Date(props.post.published_at!)
    })
})

const copyLink = async () => {
    await navigator.clipboard.writeText(window.location.href)

    toast.add({
        title: 'Link copiado!',
        icon: 'i-lucide-copy-check'
    })
}

const shareOnX = () => {
    const url = encodeURIComponent(window.location.href)
    const text = encodeURIComponent(props.post.title)
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank')
}

const handleSpoilerClick = (event: MouseEvent) => {
    const target = event.target as HTMLElement
    const spoiler = target.closest('span[data-has-spoiler="true"]')
    if (spoiler) {
        spoiler.classList.toggle('spoiler-revealed')
    }
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
                                    <span>{{ post.estimated_read_time }} min de leitura</span>
                                </div>

                                <div name="category-post">
                                    <UBadge :key="post.category.id" variant="subtle" color="primary">
                                        {{ post.category.name }}
                                    </UBadge>
                                </div>

                                <div name="features-after-post">
                                    <PostFeaturesBagde :features="featuresAfterPost" />
                                </div>
                            </div>
                        </section>
                    </header>

                    <section name="content-section">
                        <div class="relative">
                            <div v-if="typeof post.content_html === 'string'" class="prose dark:prose-invert max-w-none"
                                v-html="post.content_html" @mouseover="referencePopoverRef?.handleMouseOver($event)"
                                @click="handleSpoilerClick" />

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
                                            <div class="prose dark:prose-invert max-w-none" v-html="item.content"
                                                @mouseover="referencePopoverRef?.handleMouseOver($event)"
                                                @click="handleSpoilerClick" />
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
                    <div name="reading-progress" class="sticky top-20 space-y-6">
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
                                <UProgress v-model="readingProgress" status :max="100" size="xs">
                                    <template #status>
                                        <span>{{ readingProgress }}% {{ readingProgress >= 100 ? 'Concluído' :
                                            'Lendo...'
                                            }}</span>
                                    </template>
                                </UProgress>
                            </div>
                        </UCard>
                    </div>
                    <div name="subtopics-navigator" class="sticky top-64 mt-7 space-y-4">
                        <UCard v-if="post.sub_topics && post.sub_topics.length > 0">
                            <template #header>
                                <div class="flex items-center gap-2">
                                    <UIcon name="i-lucide-list" class="h-4 w-4 text-primary" />
                                    <span class="text-sm font-medium text-default">Índice</span>
                                </div>
                            </template>
                            <nav>
                                <ul class="space-y-1.5 text-sm">
                                    <li v-for="(topic, index) in post.sub_topics" :key="index">
                                        <a :href="`#${topic}`"
                                            class="block px-2.5 py-1.5 text-muted hover:text-default hover:bg-accented transition-colors duration-150 border-l-2 border-transparent hover:border-primary">
                                            {{ topic }}
                                        </a>
                                    </li>
                                </ul>
                            </nav>
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
.prose {
    max-width: none;
    font-size: 1.0625rem;
    line-height: 1.8;
    color: var(--ui-text);
}

.prose :deep(h2) {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    color: var(--ui-text);
    letter-spacing: -0.02em;
}

.prose :deep(h3) {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
    color: var(--ui-text);
    letter-spacing: -0.01em;
}

.prose :deep(p) {
    margin-bottom: 1rem;
    line-height: 1.75;
}

.prose :deep(pre) {
    background: var(--ui-bg-inverted);
    color: var(--ui-text-inverted);
    padding: 1.25rem;
    border-radius: 0.75rem;
    overflow-x: auto;
    margin: 1.75rem 0;
    font-size: 0.875rem;
    line-height: 1.6;
    border: 1px solid var(--ui-border);
}

.prose :deep(code) {
    font-family: var(--font-mono, monospace);
    font-size: 0.875em;
}

.prose :deep(p code) {
    background: var(--ui-bg-accented);
    padding: 0.15rem 0.4rem;
    border-radius: 0.375rem;
    color: var(--ui-primary);
    font-size: 0.85em;
    border: 1px solid var(--ui-border);
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
    color: var(--ui-primary);
}

.prose :deep(span[data-reference-id]) {
    color: var(--ui-primary);
    font-weight: 600;
    background: color-mix(in srgb, var(--ui-primary) 10%, transparent);
    cursor: help;
    padding: 0.05rem 0.15rem;
    border-radius: 0.2rem;
}

.prose :deep(p:has(img)) {
    text-align: center;
    font-size: 0.85rem;
    line-height: 1.3;
    color: var(--ui-text-dimmed);
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
    border-radius: 0.75rem;
    border: 1px solid var(--ui-border);
}

.prose :deep(img) {
    max-width: 100%;
    height: auto;
    display: inline-block;
    border-radius: 0.75rem;
    border: 1px solid var(--ui-border);
}

.prose :deep(span[data-has-spoiler="true"]) {
    position: relative;
    display: inline;
    cursor: pointer;
    user-select: none;
    color: var(--ui-text);
    transition: all 0.4s ease;
    padding: 0 2px;
}

.prose :deep(span[data-has-spoiler="true"]::before) {
    content: '';
    position: absolute;
    inset: -2px -1px;
    z-index: 1;
    border-radius: 4px;
    background-color: color-mix(in srgb, var(--ui-text-muted) 18%, transparent);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    transition: inherit;
}

.prose :deep(span[data-has-spoiler="true"]:hover::before) {
    background-color: color-mix(in srgb, var(--ui-primary) 14%, transparent);
}

.prose :deep(span[data-has-spoiler="true"].spoiler-revealed::before),
.prose :deep(span[data-has-spoiler="true"].spoiler-revealed::after) {
    opacity: 0;
    pointer-events: none;
    backdrop-filter: blur(0);
}
</style>