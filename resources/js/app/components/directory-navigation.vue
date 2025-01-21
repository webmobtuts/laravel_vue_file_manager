<script setup>
import {ref, watch} from "vue";
import {removeLastPathSegment} from "../helpers/functions.js";

    const props = defineProps({
        nav_dir: {
            type: String
        }
    })

    const emits = defineEmits(['navigate', 'selectAll', 'unselectAll']);

    const navigation_dir = ref(props.nav_dir);

    const baseDir = import.meta.env.VITE_UPLOADS_DIR;

    const navigate = () => {
        if(!navigation_dir.value) {
            return;
        }

        if(navigation_dir.value === "/" || navigation_dir.value === "\\") {
            navigation_dir.value = baseDir;
        }

        emits('navigate', navigation_dir.value);
    }

    const navigateToHome = () => {
        navigation_dir.value = baseDir;
        emits('navigate', navigation_dir.value);
    }

    const goUp = () => {
        if(navigation_dir.value === '/' || navigation_dir.value === baseDir) {
            return;
        }

        navigation_dir.value = removeLastPathSegment(navigation_dir.value) + "/";
        emits('navigate', navigation_dir.value);
    }

    const reload = () => {
        emits('navigate', navigation_dir.value);
    }

    watch(() => props.nav_dir, (newVal, oldVal) => {
        if(newVal != oldVal) {
            navigation_dir.value = newVal;
        }
    })
</script>

<template>
    <div id="navigator" class="d-flex gap-2 pt-2 pb-2">
        <div class="directory-path">
            <form @submit.prevent="navigate">
                <input type="text" placeholder="/" class="px-1" v-model="navigation_dir" />
            </form>
        </div>
        <div id="directory-home">
            <a href="#" class="text-decoration-none" @click.prevent="navigateToHome">
                <i class="bi bi-house-fill mx-1"></i>
                <span>Home</span>
            </a>
        </div>
        <div id="directory-up">
            <a href="#" class="text-decoration-none" @click.prevent="goUp" :class="{disabled: navigation_dir === '/' || navigation_dir === baseDir}">
                <i class="bi bi-arrow-up mx-1"></i>
                <span>Up one level</span>
            </a>
        </div>
        <div id="directory-reload">
            <a href="#" class="text-decoration-none" @click.prevent="reload">
                <i class="bi bi-arrow-repeat mx-1"></i>
                <span>Reload</span>
            </a>
        </div>
        <div id="directory-select-all">
            <a href="#" class="text-decoration-none" @click.prevent="$emit('selectAll')">
                <i class="bi bi-check-square mx-1"></i>
                <span>Select all</span>
            </a>
        </div>
        <div id="directory-unselect-all">
            <a href="#" class="text-decoration-none" @click.prevent="$emit('unselectAll')">
                <i class="bi bi-square mx-1"></i>
                <span>Unselect all</span>
            </a>
        </div>
    </div>
</template>
