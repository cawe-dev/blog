<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { home } from '@/routes';
</script>

<template>
    <Link :href="home()" class="flex items-center gap-2">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-inverted">
            <span class="font-mono text-sm font-bold">&lt;/&gt;</span>
        </div>
        <span class="hidden font-semibold tracking-tight text-default sm:inline-block">
            Cawe.dev
        </span>
    </Link>
</template>