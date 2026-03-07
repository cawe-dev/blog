<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { useContext } from '@/composables/useContext'
import { home } from '@/routes';

const { logoUrl } = useContext()
</script>

<template>
    <Link :href="home()" class="flex items-center gap-2">
        <img :src="logoUrl" alt="Logo do Blog">
    </Link>
</template>
