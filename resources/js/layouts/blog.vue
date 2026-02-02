<script setup lang="ts">
import Header from '@/components/blog/header.vue';
</script>

<template>
    <UApp>
        <Header />
        <UMain>
            <slot />
        </UMain>
    </UApp>
</template>
