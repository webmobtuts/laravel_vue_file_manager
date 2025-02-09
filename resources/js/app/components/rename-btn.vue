<script setup>
import {inject, ref, watch} from "vue";
import axios from "axios";
import ActionBtn from "./action-btn.vue";

const app_global = inject('app_global', {});

    const props = defineProps({
       disabled: {
           type: Boolean
       },
       selected_file: {
           type: Object
       }
    });

    const emits = defineEmits(['renameSuccess']);

    const navigation_dir = ref(app_global.value.navigation_dir);
    const showModal = ref(false);
    const filename = ref(props.selected_file ? props.selected_file.file_item.name : '');

    const toggleModal = () => {
        showModal.value = !showModal.value;
    }

    const rename = () => {
        if(!filename.value) return false;

        axios.post('/api/rename-file', {
            old_name: props.selected_file.file_item.name,
            new_name: filename.value,
            file_path: navigation_dir.value
        }).then(({data}) => {
            alert(data.message);
            emits('renameSuccess');
            filename.value = null;
            toggleModal();
        }).catch(error => {
            console.error(error);

            if(error.response.data.message) {
                alert(error.response.data.message);
            }
        });
    }

    watch(() => props.selected_file, (newval, oldval) => {
        if(newval) {
            filename.value = newval.file_item.name;
        }
    });

    watch(() => app_global.value, (newVal, oldVal) => {
        navigation_dir.value = newVal.navigation_dir;
    });
</script>

<template>
    <action-btn @click.prevent="toggleModal()" title="Rename" :class="{disabled}">
        <i class="bi bi-file-earmark-fill"></i>
        <span>Rename</span>
    </action-btn>

    <Teleport to="body">
        <div class="modal fade show" id="rename-file-modal" style="display: block" v-if="showModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rename File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" @click="toggleModal()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file-name">File name</label>
                            <input type="text" name="file_name" id="file-name" class="form-control" v-model="filename" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="toggleModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="rename">Rename</button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
