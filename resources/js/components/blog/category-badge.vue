<script setup lang="ts">
import type { IPost } from '@/types/models/post';

defineProps<{
    category: IPost['category'];
    variant?: 'solid' | 'subtle';
    color?: 'primary' | 'secondary' | 'success' | 'warning' | 'danger';
}>();
</script>

<template>
    <div name="category-post">
        <UBadge :key="category.id" :variant="variant" :color="color" class="capitalize">
            {{ category.name }}
        </UBadge>
    </div>
</template>
