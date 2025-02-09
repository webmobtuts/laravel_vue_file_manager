<script setup>
import {inject, ref, watch} from "vue";
import axios from "axios";

const app_global = inject('app_global', {});

    const props = defineProps({
       disabled: {
           type: Boolean
       },
        selected_files: {
           type: Array
       }
    });

    const emits = defineEmits(['copySuccess']);

    const navigation_dir = ref(app_global.value.navigation_dir);
    const showModal = ref(false);
    const copy_path = ref('');

    const toggleModal = () => {
        showModal.value = !showModal.value;
    }

    const copy = () => {
        if(!copy_path.value) return false;

        axios.post('/api/cp-file', {
            files: (props.selected_files ? props.selected_files.map(file => ({name: file.file_item.name, type: file.file_item.type})) : []),
            target_path: navigation_dir.value,
            dest_path: copy_path.value
        }).then(({data}) => {
            alert(data.message);
            emits('copySuccess');
            toggleModal();
            copy_path.value = "";
        }).catch(error => {
            console.error(error);
        });
    }

    watch(() => app_global.value, (newVal, oldVal) => {
        navigation_dir.value = newVal.navigation_dir;
    });
</script>

<template>

    <a class="nav-link d-flex gap-1 align-items-center" href="#" title="Copy" @click.prevent="toggleModal()" :class="{disabled}">
        <i class="bi bi-copy"></i>
        <span>Copy</span>
    </a>

    <Teleport to="body">
        <div class="modal fade show" id="rename-file-modal" style="display: block" v-if="showModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Copy File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" @click="toggleModal()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file-name">Path</label>
                            <input type="text" name="path" id="path" class="form-control" v-model="copy_path" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="toggleModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="copy">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
