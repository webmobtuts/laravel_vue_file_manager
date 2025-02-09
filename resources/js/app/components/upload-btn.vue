<script setup>
import {inject, ref, watch} from "vue";
import ActionBtn from "./action-btn.vue";

const app_global = inject('app_global', {});

const emits = defineEmits(['uploadSuccess']);

const navigation_dir = ref(app_global.value.navigation_dir);
const upload_btn = ref(null);
const file = ref(null);
const prompt_overwrite = ref(false);


const upload = ev => {
    if(!ev.target.files.length) return;

    file.value = ev.target.files[0];

    sendRequest();
}

const sendRequest = () => {
    const formData = new FormData();
    formData.append('file', file.value);
    formData.append('path', navigation_dir.value);

    if(prompt_overwrite.value) {
        formData.append('overwrite', 1);
    }

    axios.post('/api/upload', formData)
        .then(({data}) => {
            if(data.status === 'success') {
                alert("Upload success");
                emits('uploadSuccess');
                file.value = null;
            }

            prompt_overwrite.value = false;
        }).catch(error => {
            console.error(error);

            alert('Error uploading file: ' + error.response.data.message);
            prompt_overwrite.value = error.response.data.is_duplicate !== undefined;
    });
}

const reset = () => {
    file.value = null;
    prompt_overwrite.value = false;
}

const overwrite = () => {
    if(file.value) {
        sendRequest();
    }
}

watch(() => app_global.value, (newVal, oldVal) => {
    navigation_dir.value = newVal.navigation_dir;
});
</script>

<template>
    <action-btn title="Upload" @click.prevent="upload_btn.click()">
        <i class="bi bi-upload"></i>
        <span>Upload</span>
    </action-btn>
    <input type="file" name="upload_file" class="d-none" ref="upload_btn" @change="upload($event)" />

    <Teleport to="body">
        <div class="modal fade show" id="prompt-overwrite-modal" style="display: block" v-if="prompt_overwrite">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Overwrite File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" @click="reset"></button>
                    </div>
                    <div class="modal-body">
                        <p>It seems that file with same name already exist! Do you want to overwrite?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="reset">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="overwrite">Overwrite</button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
