<script setup>

import {inject, ref, watch} from "vue";

    const props = defineProps({
        type: {
            type: String
        }
    });

    const emits = defineEmits(["fileCreated"]);

    const app_global = inject('app_global', {});

    const navigation_dir = ref(app_global.value.navigation_dir);

    const name = ref('');
    const overwrite = ref(false);

    watch(() => app_global.value, (newVal, oldVal) => {
        navigation_dir.value = newVal.navigation_dir;
    });

    const create = () => {
        if(!name.value) {
            alert(`Please enter ${props.type === 'file'?'file':'folder'}`);
            return;
        }

        axios.post('/api/new-file', {
            path: navigation_dir.value,
            filename: name.value,
            overwrite: overwrite.value,
            type: props.type,
        })
            .then(({data}) => {
                emits('fileCreated', {filepath: data.data});
                name.value = "";
                overwrite.value = false;
                document.querySelector('#new-file-modal .btn-close').click();
            }).catch((error) => {
                alert("There was an error creating the file/folder: " + error.response.data.message);
                console.error(error.response.data.message);
            });
    }
</script>

<template>
        <div class="modal fade" id="new-file-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create {{type==='file' ? 'File' : 'Folder'}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{type==='file' ? 'File' : 'Folder'}} Name</label>
                            <input type="text" class="form-control" name="file_name" v-model="name" />
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="overwrite" name="overwrite" value="1" v-model="overwrite" />
                            <label class="form-check-label" for="overwrite">Overwrite if exist</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="create">Create</button>
                    </div>
                </div>
            </div>
        </div>
</template>
