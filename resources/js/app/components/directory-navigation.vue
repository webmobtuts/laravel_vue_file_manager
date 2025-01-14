<script setup>

</script>

<template>
    <div id="navigator" class="d-flex gap-2 pt-2 pb-2">
        <div class="directory-path">
            <form>
                <input type="text" placeholder="/" class="px-1" />
            </form>
        </div>
        <div id="directory-home">
            <a href="#" class="text-decoration-none">
                <i class="bi bi-house-fill mx-1"></i>
                <span>Home</span>
            </a>
        </div>
        <div id="directory-up">
            <a href="#" class="text-decoration-none">
                <i class="bi bi-arrow-up mx-1"></i>
                <span>Up one level</span>
            </a>
        </div>
        <div id="directory-back">
            <a href="#" class="text-decoration-none">
                <i class="bi bi-arrow-left mx-1"></i>
                <span>Back</span>
            </a>
        </div>
        <div id="directory-forward">
            <a href="#" class="text-decoration-none">
                <i class="bi bi-arrow-right mx-1"></i>
                <span>Forward</span>
            </a>
        </div>
        <div id="directory-select-all">
            <a href="#" class="text-decoration-none">
                <i class="bi bi-check-square mx-1"></i>
                <span>Select all</span>
            </a>
        </div>
        <div id="directory-unselect-all">
            <a href="#" class="text-decoration-none">
                <i class="bi bi-square mx-1"></i>
                <span>Unselect all</span>
            </a>
        </div>
        <div id="directory-reload">
            <a href="#" class="text-decoration-none">
                <i class="bi bi-arrow-repeat mx-1"></i>
                <span>Reload</span>
            </a>
        </div>
    </div>
</template>
