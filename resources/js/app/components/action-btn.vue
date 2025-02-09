<script setup>
    defineProps(['title']);
</script>

<template>
    <a class="nav-link d-flex gap-1 align-items-center" href="#" :title="title">
        <slot />
    </a>
</template>
