<script setup>
    import {inject, ref, watch} from "vue";
    import CreateFileModal from "./create-file-modal.vue";
    import UploadBtn from "./upload-btn.vue";
    import RenameBtn from "./rename-btn.vue";
    import CopyMoveBtn from "./copy-move-btn.vue";
    import ActionBtn from "./action-btn.vue";
    import EditBtn from "./edit-btn.vue";

    const emits = defineEmits(['reload', 'download', 'delete']);
    const props = defineProps({
        selected: Array
    });
    const file_type = ref('file');

    const app_global = inject('app_global', {});

    const navigation_dir = ref(app_global.value.navigation_dir);

    const isDownloadDisabled = () => {
        if(!props.selected.length) return true;

        return !!(props.selected && props.selected.filter(s => s.file_item.type === 'directory').length);
    }

    const isEditDisabled = () => {
        if(!props.selected.length) return true;

        return props.selected.length > 1 || props.selected.filter(s => s.file_item.type === 'directory').length > 0;
    }

    watch(() => app_global.value, (newVal, oldVal) => {
        navigation_dir.value = newVal.navigation_dir;
    });
</script>

<template>
    <ul class="navbar-nav controls">
        <li class="nav-item control dropdown">
            <action-btn title="New" class="dropdown-toggle" data-bs-toggle="dropdown" >
                <i class="bi bi-plus-lg"></i>
                <span>New</span>
            </action-btn>
            <ul class="dropdown-menu">
                <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#new-file-modal" @click="file_type='file'">File</a></li>
                <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#new-file-modal" @click="file_type='folder'">Folder</a></li>
            </ul>
        </li>
        <li class="nav-item control">
            <upload-btn @upload-success="$emit('reload')" />
        </li>
        <li class="nav-item control">
            <action-btn title="Download" @click.prevent="$emit('download')" :class="{disabled: isDownloadDisabled()}">
                <i class="bi bi-download"></i>
                <span>Download</span>
            </action-btn>
        </li>
        <li class="nav-item control">
            <action-btn title="Delete" @click.prevent="$emit('delete')" :class="{disabled: selected.length === 0}">
                <i class="bi bi-x-lg"></i>
                <span>Delete</span>
            </action-btn>
        </li>
        <li class="nav-item control">
            <EditBtn
                :disabled="isEditDisabled()"
                :selected="selected ? selected[0]: null"
                @edit-success="$emit('reload')"
            />
        </li>
        <li class="nav-item control">
            <rename-btn
                :disabled="selected.length === 0 || selected.length > 1"
                :selected_file="selected.length ? selected[0] : null"
                @rename-success="$emit('reload')"
            />
        </li>
        <li class="nav-item control">
            <copy-move-btn
                :disabled="selected.length === 0"
                :selected_files="selected"
                action="copy"
                @copy-success="$emit('reload')"
                >
                <i class="bi bi-copy"></i>
                <span>Copy</span>
            </copy-move-btn>
        </li>
        <li class="nav-item control">
            <copy-move-btn
                :disabled="selected.length === 0"
                :selected_files="selected"
                action="move"
                @copy-success="$emit('reload')"
            >
                <i class="bi bi-arrows-move"></i>
                <span>Move</span>
            </copy-move-btn>
        </li>
    </ul>

    <Teleport to="body">
        <create-file-modal :type="file_type" @file-created="$emit('reload')" />
    </Teleport>
</template>
