<script setup>
    import {ref} from "vue";
    import FileRow from "./file-row.vue";

    const file_list = ref([
        {
            name: '..',
            type: 'directory',
            size: '',
            modified_date: '',
            mime_type: '',
            permission: ''
        },
        {
            name: 'Directory',
            type: 'directory',
            size: '4 KB',
            modified_date: 'Yesterday, 1:06 PM',
            mime_type: 'httpd/unix-directory',
            permission: '0755'
        },
        {
            name: 'Directory',
            type: 'directory',
            size: '4 KB',
            modified_date: 'Yesterday, 1:06 PM',
            mime_type: 'httpd/unix-directory',
            permission: '0755'
        },
        {
            name: 'File',
            type: 'file-php',
            size: '30 KB',
            modified_date: 'Yesterday, 1:06 PM',
            mime_type: 'text/x-generic',
            permission: '0644'
        },
        {
            name: 'File',
            type: 'file-js',
            size: '30 KB',
            modified_date: 'Yesterday, 1:06 PM',
            mime_type: 'text/x-generic',
            permission: '0644'
        },
        {
            name: 'File',
            type: 'file-generic',
            size: '30 KB',
            modified_date: 'Yesterday, 1:06 PM',
            mime_type: 'text/x-generic',
            permission: '0644'
        }
    ]);
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

                <FileRow v-for="(file_item, index) in file_list" :key="index" :fileItem="file_item" />

            </tbody>
        </table>
    </div>
</template>
