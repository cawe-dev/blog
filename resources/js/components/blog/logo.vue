<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { home } from '@/routes';
import { useContext } from '@/composables/useContext'

const { logoUrl } = useContext()
</script>

<template>
    <Link :href="home()" class="flex items-center gap-2">
        <img :src="logoUrl" alt="Logo do Blog">
    </Link>
</template>
