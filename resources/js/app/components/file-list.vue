<script setup>
    import FileRow from "./file-row.vue";

    const props = defineProps({
        file_list: {
            type: Array
        },
        selected_files: {
            type: Array
        }
    });

    const emits = defineEmits(['selectFileSingle', 'selectFileRange', 'selectMultipleFiles', 'onDblClickFile']);

    const is_selected = (index) => {
       return (props.selected_files.length && (props.selected_files.find((f) => f.index === index) !== undefined))===true;
    };

    const handleSelectFile = (event, index, file_item) => {
        if(event.ctrlKey) {           // Triggered if clicked while holding ctrl key
            emits('selectMultipleFiles', {index, file_item})
        } else if(event.shiftKey) {   // Triggered if clicked while holding shift key
            emits('selectFileRange', {index, file_item})
        } else {                      // Triggered if clicked only
            emits('selectFileSingle', {index, file_item})
        }
    }
</script>

<template>
    <div id="file-list" class="mt-3">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                    <th>Type</th>
                    <th>Permissions</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">

                <FileRow v-for="(file_item, index) in file_list"
                         :key="index"
                         :fileItem="file_item"
                         @click.prevent="handleSelectFile($event, index,  file_item)"
                         @dblclick="$emit('onDblClickFile', file_item)"
                         :is_selected="is_selected(index)" />

            </tbody>
        </table>
    </div>
</template>
