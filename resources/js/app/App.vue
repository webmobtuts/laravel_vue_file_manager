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

        <DirectoryNavigation
            :nav_dir="navigation_dir"
            @navigate="navigateToDir($event)"
            @select-all="handleSelectAll"
            @unselect-all="clearSelectedFiles"
        />

        <FileList :file_list="file_list"
                  @onDblClickFile="handleDblClickFile"
                  :selected_files="selected_files"
                  @select-file-single="handleSelectSingleFile"
                  @select-multiple-files="handleSelectFileWithCtrl"
                  @select-file-range="handleSelectFileWithShift"
        />
    </div>
</template>

<script setup>
import {ref} from "vue";
import FileActions from "./components/file-actions.vue";
import DirectoryNavigation from "./components/directory-navigation.vue";
import FileList from "./components/file-list.vue";
import {useNavigation} from "./composables/useNavigation.js";
import {useSelectedFiles} from "./composables/useSelectedFiles.js";
import {removeLastPathSegment} from "./helpers/functions.js";

const navigation_dir = ref(import.meta.env.VITE_UPLOADS_DIR);

const {file_list, fetchFiles} = useNavigation(navigation_dir.value);

const {selected_files, handleSelectSingleFile, handleSelectFileWithShift,
    handleSelectFileWithCtrl, clearSelectedFiles, handleSelectAll} = useSelectedFiles(file_list);

const navigateToDir = (ev) => {
    fetchFiles(ev);

    navigation_dir.value = ev;   // update the navigation_dir value

    clearSelectedFiles();
}

const handleDblClickFile = (ev) => {
    if(ev.type === 'directory') {   // If type=directory open it
        if(ev.name === '..') {     // if name=".." then go to previous directory
            navigation_dir.value = removeLastPathSegment(navigation_dir.value) + "/";
        } else {
            const append_dir = (navigation_dir.value.endsWith('/') ? navigation_dir.value : navigation_dir.value+'/') + ev.name + "/";
            navigation_dir.value = append_dir;
        }

        fetchFiles(navigation_dir.value);
        clearSelectedFiles();
    }
}

</script>
