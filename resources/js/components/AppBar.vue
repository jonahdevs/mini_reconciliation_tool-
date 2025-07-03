<script setup>
import { Link } from '@inertiajs/vue3';

// script here
const links = [
    {
        title: 'Home',
        href: route('home')
    },
    {
        title: 'Compare',
        href: route('reconciliation')
    },
    {
        title: 'Samples',
        href: route('reconciliation')
    },
]
</script>
<template>
    <header class="bg-muted text-muted-foreground">
        <nav class="container mx-auto max-w-6xl flex items-center py-5">
            <h2 class="font-bold text-xl">Mini Reconciliation Tool</h2>

            <div class="flex items-center ms-auto gap-5">
                <template v-for="(link, i) in links" :key="i">
                    <Link :href="link.href"
                        class="text-sm relative before:absolute before:w-0 before:h-[2px] before:top-full before:bg-muted-foreground before:rounded-full before:transition-[width] before:duration-300 before:ease-in-out hover:before:w-full">
                    {{ link.title }}
                    </Link>
                </template>
            </div>
        </nav>
    </header>
</template>
<style scoped></style>