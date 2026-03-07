import { computed, ref, type Ref } from 'vue'
import { useContext } from '@/composables/useContext'
import type { ICategory } from '@/types/models/category'
import type { IPost, IPostGroup } from '@/types/models/post'
import type { ITag } from '@/types/models/tag'

export const usePostFeed = (postsProp: Ref<IPostGroup>) => {
    const searchQuery = ref('')
    const selectedCategories = ref<string[]>([])
    const selectedTags = ref<string[]>([])
    const viewMode = ref<'grid' | 'list'>('list')

    const { context } = useContext()

    const allTags = computed(() => {
        const tagsMap = new Map<number, ITag>()
        const allPosts = [
            ...(postsProp.value.personalPosts || []),
            ...(postsProp.value.professionalPosts || []),
            ...(postsProp.value.bothPosts || [])
        ];
        allPosts.forEach(post => {
            if (post.tags) {
                post.tags.forEach(tag => {
                    if (!tagsMap.has(tag.id)) {
                        tagsMap.set(tag.id, tag)
                    }
                })
            }
        })
        return Array.from(tagsMap.values()).sort((a, b) => a.name.localeCompare(b.name));
    })

    const allCategories = computed(() => {
        const categoriesMap = new Map<number, ICategory>()
        const allPosts = [
            ...(postsProp.value.personalPosts || []),
            ...(postsProp.value.professionalPosts || []),
            ...(postsProp.value.bothPosts || []),
        ];
        allPosts.forEach(post => {
            if (post.category) {
                if (!categoriesMap.has(post.category.id)) {
                    categoriesMap.set(post.category.id, post.category)
                }
            }
        })
        return Array.from(categoriesMap.values()).sort((a, b) => a.name.localeCompare(b.name))
    })

    const filteredPosts = computed(() => {
        const bothPinnedsPosts = postsProp.value.bothPinnedsPosts || []
        const professionalPosts = postsProp.value.professionalPosts || []
        const personalPosts = postsProp.value.personalPosts || []
        const bothPosts = postsProp.value.bothPosts || []

        let basePosts: IPost[] = []
        if (context.value === 'professional') {
            basePosts = [...bothPinnedsPosts, ...professionalPosts, ...bothPosts]
        } else {
            basePosts = [...bothPinnedsPosts, ...personalPosts, ...bothPosts]
        }

        let posts = basePosts;

        if (searchQuery.value) {
            const query = searchQuery.value.toLowerCase()
            posts = posts.filter(p =>
                p.title.toLowerCase().includes(query) ||
                p.excerpt?.toLowerCase().includes(query)
            )
        }

        if (selectedCategories.value.length > 0) {
            posts = posts.filter(p =>
                p.category.slug && (p.category.slug?.includes(selectedCategories.value))
            )
        }

        if (selectedTags.value.length > 0) {
            posts = posts.filter(p =>
                p.tags && p.tags.some(tag => selectedTags.value.includes(tag.slug))
            )
        }

        return posts
    })

    return {
        searchQuery,
        selectedCategories,
        selectedTags,
        viewMode,
        allCategories,
        allTags,
        filteredPosts
    }
}