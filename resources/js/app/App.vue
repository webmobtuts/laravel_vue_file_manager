<template>
    <div id="file-manager">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">File Manager</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <FileActions
                        @reload="reload"
                        @download="handleDownload"
                        @delete="handleDelete"
                        :selected="selected_files" />
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

        <div id="download-links" class="d-none"></div>
    </div>
</template>

<script setup>
import {computed, provide, ref} from "vue";
import FileActions from "./components/file-actions.vue";
import DirectoryNavigation from "./components/directory-navigation.vue";
import FileList from "./components/file-list.vue";
import {useNavigation} from "./composables/useNavigation.js";
import {useSelectedFiles} from "./composables/useSelectedFiles.js";
import {removeLastPathSegment} from "./helpers/functions.js";
import axios from "axios";

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
    } else {
        download([ev.name]);
    }
}

const download = (files) => {
    document.querySelector("#download-links").innerHTML = '';

    axios.post(`/api/get-file-urls`, {
        path: navigation_dir.value,
        files
    }).then(({data}) => {
        let html = '';
        const file_urls = data.data;
        file_urls.forEach(file => {
            html += `<a href="${file}" target="_blank">download</a>`;
        });

        if(html) document.querySelector("#download-links").innerHTML = html;

        document.querySelectorAll("#download-links a").forEach(link => {
            link.click();
        });

        document.querySelector("#download-links").innerHTML = '';
    }).catch(error => {
        console.error(error);
    });
}

const handleDownload = () => {
    const files = selected_files.value.map(sel => sel.file_item.name);

    download(files);
}

const handleDelete = () => {
    if(!confirm(`You will delete ${selected_files.value.length} files! Are you sure you want to continue`)) {
        return false;
    }

    axios.post(`/api/del-files`, {
        files: selected_files.value.map(file => ({type: file.file_item.type, name: file.file_item.name})),
        file_path: navigation_dir.value
    }).then(({data}) => {
        alert(data.message);

        reload();

        clearSelectedFiles();
    }).catch(error => {
        console.error(error);
    });
}

const reload = () => {
    fetchFiles(navigation_dir.value);
    clearSelectedFiles();
}

provide('app_global', computed(() => ({
    navigation_dir: navigation_dir.value
    })
));

</script>
