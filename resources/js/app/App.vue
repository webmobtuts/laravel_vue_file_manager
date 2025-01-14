<template>
    <div id="file-manager">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">File Manager</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <FileActions />
                </div>
            </div>
        </nav>

        <DirectoryNavigation />

        <FileList />
    </div>
</template>

<script setup>
import FileActions from "./components/file-actions.vue";
import DirectoryNavigation from "./components/directory-navigation.vue";
import FileList from "./components/file-list.vue";
</script>
