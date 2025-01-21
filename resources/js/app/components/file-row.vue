<script setup>
    const props = defineProps({
       fileItem: {
           type: Object
       },
        is_selected: {
           type: Boolean,
           default: false
        }
    });

    const getFileClass = () => {
        switch (props.fileItem.type) {
            case 'directory':
                return 'file-type-directory bi-folder-fill';
            case 'file-image':
                return 'file-type-file bi-image';
            case 'file-php':
                return 'file-type-file bi-filetype-php';
            case 'file-txt':
                return 'file-type-file bi-filetype-txt';
            case 'file-js':
                return 'file-type-file bi-filetype-js';
            case 'file-css':
                return 'file-type-file bi-filetype-css';
            default:
                return 'file-type-file bi-file';
        }
    }
</script>

<template>
    <tr class="file-row" :class="{'bg-primary': is_selected}" >
        <td>
            <div class="file-name">
                <i class="bi file-icon " :class="getFileClass()"></i>
                <span>{{fileItem.name}}</span>
            </div>
        </td>
        <td>
            <span>{{fileItem.size}}</span>
        </td>
        <td>
            <span>{{fileItem.modified_date}}</span>
        </td>
        <td>
            {{fileItem.mime_type}}
        </td>
        <td>
            <span>{{fileItem.permission}}</span>
        </td>
    </tr>
</template>
