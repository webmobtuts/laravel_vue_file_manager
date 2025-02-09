<script setup>
import {inject, ref, watch} from "vue";
import ActionBtn from "./action-btn.vue";

const app_global = inject('app_global', {});

const emits = defineEmits(['editSuccess']);
const props = defineProps({
    disabled: {
        type: Boolean
    },
    selected: {
        type: Object
    }
});

const navigation_dir = ref(app_global.value.navigation_dir);
const showModal = ref(false);
const contents = ref("");
const file = ref(props.selected);

const sendRequest = () => {
    axios.post(`/api/edited-file`, {
        file_path: navigation_dir.value + file.value.file_item.name,
        mime_type: file.value.file_item.mime_type
    }).then(({data}) => {
            showModal.value = true;
            contents.value = data.contents;
    }).catch(error => {
        alert(error.response.data.message);
    });
}

const saveEdit = () => {
    axios.post(`/api/save-edited-file`, {
        file_path: navigation_dir.value + file.value.file_item.name,
        contents: contents.value
    }).then(({data}) => {
        alert(data.message);
        toggleModal();
        emits('editSuccess');
    }).catch(error => {
        alert(error.response.data.message);
    });
}

const toggleModal = () => {
    showModal.value = !showModal.value;
}

watch(() => app_global.value, (newVal, oldVal) => {
    navigation_dir.value = newVal.navigation_dir;
});

watch(() => props.selected, (newVal, oldVal) => {
    file.value = newVal;
});
</script>

<template>
    <action-btn title="Edit" :class="{disabled}" @click.prevent="sendRequest">
        <i class="bi bi-pencil-fill"></i>
        <span>Edit</span>
    </action-btn>

    <teleport to="body">
        <div class="modal fade show" id="edit-file-modal" style="display: block" v-if="showModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" @click="toggleModal()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea v-model="contents" rows="20" cols="70" style="width: 100%">
                            </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="toggleModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="saveEdit">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>

