<script setup lang="ts">
import { toRefs } from 'vue'
import Layout from '@/layouts/blog.vue'
import { useContext } from '@/composables/useContext'
import { usePostFeed } from '@/composables/usePostFeed'
import type { IPostGroup } from '@/types/models/post'
import PostCard from '@/components/blog/post-card.vue'

interface Props {
    posts: IPostGroup;
}

const props = defineProps<Props>();

defineOptions({ layout: Layout })

const { context } = useContext()

const { posts: postsProp } = toRefs(props)
const {
    filteredPosts,
    searchQuery,
    allCategories,
    selectedCategories,
    viewMode,
} = usePostFeed(postsProp)

</script>

<template>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6">
        <section class="mb-8 rounded-xl border border-default bg-elevated p-6 sm:p-8 transition-colors duration-300">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-default">
                        <template v-if="context === 'professional'">
                            Código, Arquitetura & <span class="text-(--color-pro-500)">Engenharia</span>
                        </template>
                        <template v-else>
                            Vida, Hobbies & <span class="text-(--color-life-500)">Descobertas</span>
                        </template>
                    </h1>
                    <p class="mt-2 text-muted">
                        {{ context === 'professional'
                            ? 'Explorando o universo do desenvolvimento de software de alta performance.'
                            : 'Um olhar pessoal sobre música, jogos e o equilíbrio da vida.' }}
                    </p>
                </div>
            </div>
        </section>

        <div class="flex gap-6 items-start">

            <main class="flex-1 min-w-0">
                <div class="mb-6 flex flex-wrap gap-3 items-center justify-between">
                    <div class="flex gap-2">
                        <UInput v-model="searchQuery" icon="i-lucide-search" placeholder="Buscar..." class="w-48" />
                        <USelectMenu multiple v-model="selectedCategories" :items="allCategories" value-key="slug"
                            label-key="name" searchable placeholder="Selecione categoria(s)..." />
                    </div>

                    <div class="flex border border-default rounded-md gap-0.5 p-1">
                        <UButton :variant="viewMode === 'grid' ? 'solid' : 'ghost'" color="primary"
                            icon="i-lucide-layout-grid" @click="viewMode = 'grid'" size="sm" />
                        <UButton :variant="viewMode === 'list' ? 'solid' : 'ghost'" color="primary" icon="i-lucide-list"
                            @click="viewMode = 'list'" size="sm" />
                    </div>
                </div>

                <div :class="viewMode === 'grid' ? 'grid gap-4 sm:grid-cols-2' : 'flex flex-col gap-4'">
                    <PostCard v-for="post in filteredPosts" :key="post.id" :post="post" :view-mode="viewMode" />
                </div>

                <div v-if="!filteredPosts.length" class="py-12 text-center text-muted">
                    Nenhum post encontrado para os filtros atuais.
                </div>
            </main>
        </div>
    </div>
</template>
