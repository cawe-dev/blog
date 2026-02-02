<script setup lang="ts">
const searchOpen = defineModel<boolean>('searchOpen')

const searchQuery = defineModel<string>('searchQuery')
</script>

<template>
    <div class="relative hidden sm:block">
        <div v-if="searchOpen" class="flex items-center gap-2">
            <UInput v-model="searchQuery" type="search" placeholder="Pesquisar posts, tags..." class="h-9 w-64" autofocus
                icon="i-lucide-search" />
            <UButton variant="ghost" size="sm" icon="i-lucide-x" square @click="searchOpen = false" />
        </div>
        <UButton v-else variant="ghost" size="sm" icon="i-lucide-search" square @click="searchOpen = true">
            <span class="sr-only">Pesquisar</span>
        </UButton>
    </div>
</template>