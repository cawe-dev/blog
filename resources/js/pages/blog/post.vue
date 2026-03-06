<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import Layout from '@/layouts/blog.vue'
import { Link } from '@inertiajs/vue3'
import type { IPost } from '@/types/models/post'
import type { IViewMode, ViewModeKeys } from '@/types/enums/contentPostViewMode'
import { VIEW_MODE_CONFIG } from '@/types/enums/contentPostViewMode'
import ReferencePopover from '@/components/blog/reference-popover.vue'
import { IChangelogs, IChangelogItem } from '@/types/models/changeLog'
import PostFeaturesBagde from '@/components/blog/PostFeaturesBagde.vue'
import { useContext } from '@/composables/useContext'
import { formatDate } from '@/utils/date'
import Topics from '@/components/blog/topics.vue'

const props = defineProps<{
    post: IPost,
    changelogs: IChangelogs
}>()

defineOptions({ layout: Layout })

const toast = useToast()
const { font, lineLength } = useContext()

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
    return formatDate(props.post.created_at)
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
    return props.changelogs.map((changelog: IChangelogItem) => {
        return changelog
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

        <div class="sm:grid sm:grid-cols-[700px_minmax(500px,1fr)_700px]">
            <aside class="hidden w-full shrink-0 lg:block">
            </aside>

            <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
                <div class="flex gap-8">
                    <article class="text-pretty">
                        <header class="mb-8">
                            <Link href="/"
                                class="mb-4 inline-flex items-center gap-1.5 text-sm text-default hover:text-accented">
                                <UIcon name="i-lucide-arrow-left" class="h-4 w-4" />
                                Voltar ao Blog
                            </Link>
                            <h1
                                class="mt-4 mb-4 text-balance text-2xl font-bold leading-tight text-highlighted sm:text-4xl lg:text-5xl">
                                {{ post.title }}
                            </h1>

                            <section name="metadata-section">
                                <div class="mb-6 flex flex-wrap items-center gap-3 text-sm">
                                    <div name="create-date" class="flex items-center gap-1.5 text-muted">
                                        <UIcon name="i-lucide-calendar" class="h-4 w-4" />
                                        <time :datetime="post.created_at">{{ formattedDate }}</time>
                                    </div>

                                    <UBadge name="edited-post-indicator" v-if="wasEdited" variant="subtle"
                                        color="secondary" size="sm">
                                        <UIcon name="i-lucide-pencil" class="mr-1 h-3 w-3" />
                                        Editado em {{ formattedUpdateDate }}
                                    </UBadge>

                                    <div name="esmative-read-time" class="flex items-center gap-1.5 text-muted">
                                        <UIcon name="i-lucide-clock" class="h-4 w-4" />
                                        <span>{{ post.estimated_read_time }} min de leitura</span>
                                    </div>

                                    <div name="category-post">
                                        <UBadge :key="post.category.id" variant="subtle" color="secondary">
                                            {{ post.category.name }}
                                        </UBadge>
                                    </div>

                                    <div v-if="featuresAfterPost.length > 0" name="features-after-post">
                                        <PostFeaturesBagde :features="featuresAfterPost" />
                                    </div>
                                </div>
                            </section>
                        </header>

                        <section name="content-section">
                            <div>
                                <div v-if="typeof post.content_html === 'string'" class="mx-auto px-3 sm:px-0"
                                    :class="lineLength">
                                    <div class="prose dark:prose-invert" :class="font" v-html="post.content_html"
                                        @mouseover="referencePopoverRef?.handleMouseOver($event)"
                                        @click="handleSpoilerClick" />
                                </div>

                                <div v-else class="mx-auto px-3" :class="lineLength">
                                    <UTabs v-model="viewMode" :items="viewModeItems" size="sm" class="lg:hidden mt-12"
                                        variant="pill" color="secondary">
                                        <template #content="{ item }">
                                            <div class="mt-8">
                                                <div class="prose dark:prose-invert" :class="font" v-html="item.content"
                                                    @mouseover="referencePopoverRef?.handleMouseOver($event)"
                                                    @click="handleSpoilerClick" />
                                            </div>
                                        </template>
                                    </UTabs>

                                    <div class="hidden lg:block">
                                        <template v-for="item in viewModeItems" :key="item.value">
                                            <div v-show="viewMode === item.value">
                                                <h3 class="mb-4 text-sm font-bold uppercase tracking-widest text-muted">
                                                    {{ item.label }}
                                                </h3>
                                                <div class="prose max-w-none dark:prose-invert" :class="font"
                                                    v-html="item.content"
                                                    @mouseover="referencePopoverRef?.handleMouseOver($event)"
                                                    @click="handleSpoilerClick" />
                                            </div>
                                        </template>
                                    </div>
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
                </div>
            </div>

            <aside class="hidden w-64 shrink-0 lg:block mt-18">
                <div class="sticky top-24 space-y-6">
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

                    <UCard v-if="typeof post.content_html !== 'string'" name="view-mode-widget">
                        <template #header>
                            <div class="flex items-center gap-2">
                                <UIcon name="i-lucide-layers" class="h-4 w-4 text-primary" />
                                <span class="text-sm font-medium text-default">Modo de Visualização</span>
                            </div>
                        </template>
                        <UTabs v-model="viewMode" :items="viewModeItems" orientation="vertical" :content="false"
                            variant="link" class="w-full" />
                    </UCard>

                    <Topics :subTopics="post.sub_topics" />
                </div>
            </aside>
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
    line-height: 1.6;
    color: var(--ui-text);
    -webkit-font-smoothing: antialiased;
}

.prose.font-professional {
    font-family: 'Inter', ui-sans-serif, system-ui;
    letter-spacing: -0.01em;
    line-height: 1.7;
}

.prose.font-personal {
    font-family: 'Charter', 'Bitstream Charter', 'Sitka Text', Georgia, serif;
}

.font-personal h1,
.font-personal h2,
.font-personal h3 {
    font-family: 'Inter', sans-serif;
    letter-spacing: -0.02em;
}

.prose :deep(h2) {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--ui-text);
    letter-spacing: -0.02em;
    line-height: 1.5;
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
    margin-bottom: 1.5rem;
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

.prose :deep(span[data-reference-id]) {
    font-weight: 500 !important;
    text-decoration-line: underline;
    text-decoration-style: dashed;
    text-decoration-color: var(--ui-primary);
    text-decoration-thickness: 1px;
    text-underline-offset: 2px;
    cursor: help;

    border-radius: 4px;
    padding: 0 2px;
    transition: all 0.2s ease;
}

.prose :deep(span[data-reference-id]:hover) {
    color: var(--ui-primary);
    text-decoration-style: solid;
    text-decoration-color: var(--ui-primary);
}

.prose :deep(iframe) {
    display: block;
    width: 100%;
    aspect-ratio: 16 / 9;
    margin-inline: auto;
    border-radius: 0.75rem;
    border: 1px solid var(--ui-border);
}

.prose :deep(p:has(img[data-type="img"])) {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    font-size: 0.85rem;
    line-height: 1.3;
    color: var(--ui-text-toned);
    font-style: italic;
}

.prose :deep(img[data-type="img"]) {
    max-width: 100%;
    height: auto;
    display: block;
    border-radius: 0.75rem;
    border: 1px solid var(--ui-border);
    margin-bottom: 0.7rem;
    font-style: normal;
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