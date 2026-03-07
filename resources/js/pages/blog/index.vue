<script setup lang="ts">
import { toRefs } from 'vue';
import PostCard from '@/components/blog/post-card.vue';
import { useContext } from '@/composables/useContext';
import { usePostFeed } from '@/composables/usePostFeed';
import Layout from '@/layouts/blog.vue';
import type { IPostGroup } from '@/types/models/post';

interface Props {
    posts: IPostGroup;
}

const props = defineProps<Props>();

defineOptions({ layout: Layout });

const { context } = useContext();

const { posts: postsProp } = toRefs(props);
const { filteredPosts, searchQuery, allCategories, selectedCategories, viewMode } = usePostFeed(postsProp);
</script>

<template>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6">
        <section name="first-contact-text">
            <div class="my-10">
                <div>
                    <template v-if="context === 'professional'">
                        <img src="/images/blog-profissional-intro.svg" alt="Logo do Blog Profissional" />
                    </template>
                    <template v-else>
                        <img src="/images/blog-personal-intro.svg" alt="Logo do Blog Pessoal" />
                    </template>
                    <p class="text-muted">
                        {{
                            context === 'professional'
                                ? `Explorando features, padrões, design, e outros aspectos do desenvolvimento de software de
                        forma incremental neste
                        site.`
                                : `Falando sobre tudo o que não é profissional.`
                        }}
                    </p>
                </div>
            </div>
        </section>

        <div class="flex items-start gap-6">
            <main class="min-w-0 flex-1">
                <section name="filters">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <div name="category-filter" class="flex flex-row gap-1 sm:gap-4">
                            <div name="search-filter" class="">
                                <UInput v-model="searchQuery" icon="i-lucide-search" placeholder="Buscar..." class="sm:w-48" />
                            </div>

                            <div name="category-filter">
                                <USelectMenu
                                    multiple
                                    v-model="selectedCategories"
                                    :items="allCategories"
                                    value-key="slug"
                                    label-key="name"
                                    searchable
                                    placeholder="Selecione categoria(s)..."
                                />
                            </div>
                        </div>

                        <div class="hidden gap-0.5 rounded-md border border-default p-1 sm:flex">
                            <UButton
                                :variant="viewMode === 'grid' ? 'solid' : 'ghost'"
                                color="primary"
                                icon="i-lucide-layout-grid"
                                @click="viewMode = 'grid'"
                                size="sm"
                            />
                            <UButton
                                :variant="viewMode === 'list' ? 'solid' : 'ghost'"
                                color="primary"
                                icon="i-lucide-list"
                                @click="viewMode = 'list'"
                                size="sm"
                            />
                        </div>
                    </div>
                </section>

                <section name="posts">
                    <div :class="viewMode === 'grid' ? 'grid gap-4 sm:grid-cols-2' : 'flex flex-col gap-4'">
                        <PostCard v-for="post in filteredPosts" :key="post.id" :post="post" :view-mode="viewMode" />
                    </div>
                </section>

                <section name="posts-not-found">
                    <div v-if="!filteredPosts.length" class="py-12 text-center text-muted">Nenhum post encontrado para os filtros atuais.</div>
                </section>
            </main>
        </div>
    </div>
</template>
