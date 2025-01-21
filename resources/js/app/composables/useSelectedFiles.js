import {ref} from "vue";

export const useSelectedFiles = (file_list) => {
    const selected_files = ref([]);

    const clearSelectedFiles = () => {
        selected_files.value = [];
    }

    const handleSelectSingleFile = (ev) => {
        selected_files.value = [];
        selected_files.value.push(ev);
    }

    const handleSelectFileWithCtrl = (ev) => {
        if(selected_files.value.find((f) => f.index===ev.index)) {
            selected_files.value = selected_files.value.filter((f) => f.index !== ev.index);
        } else {
            selected_files.value.push(ev);
        }
    }

    const handleSelectFileWithShift = (ev) => {
        if(!selected_files.value.length) {
            selected_files.value.push(ev);
            return;
        }

        // If select a file with index greater than the selected files first file index
        if(ev.index > selected_files.value[0].index) {
            for(var i=selected_files.value[0].index; i<=ev.index; i++) {
                if(i !== selected_files.value[0].index && selected_files.value.find((f) => f.index === i) === undefined) {
                    selected_files.value.push({index: i, file_item: file_list.value[i] });
                }
            }
        }

        // If select a file with index lower than the selected files last file index
        if(ev.index < selected_files.value[selected_files.value.length-1].index) {
            for(var j=selected_files.value[selected_files.value.length-1].index; j >= ev.index; j--) {
                if(selected_files.value.find((f) => f.index === j) === undefined) {
                    selected_files.value.push({index: j, file_item: file_list.value[j] });
                }
            }
        }
    }

    const handleSelectAll = () => {
        selected_files.value = [];
        selected_files.value = file_list.value.map((item, index) => ({index, file_item: item}));
    }

    return {
        selected_files,
        handleSelectSingleFile,
        handleSelectFileWithCtrl,
        handleSelectFileWithShift,
        clearSelectedFiles,
        handleSelectAll
    }
}
