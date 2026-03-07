<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

const navItems = [{ label: 'Blog', to: home() }];
</script>

<template>
    <nav class="hidden items-center gap-6 md:flex">
        <Link v-for="item in navItems" :href="item.to" :key="item.label" class="text-sm font-medium text-muted transition-colors hover:text-default">
            {{ item.label }}
        </Link>
    </nav>
</template>
