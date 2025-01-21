import {onMounted, ref} from "vue";
import axios from "axios";

export const useNavigation = (navigation_dir)=> {
    const file_list = ref([]);

    const fetchFiles = (navigation_dir) => {
        axios.get(`/api/navigate?dir=${navigation_dir}`)
            .then(({data}) => {
                if(data.status === 'success') {
                    file_list.value = data.data;
                }
            }).catch(({response}) => {
            if (response) {
                alert(response.data.message);
            }
        });
    };

    onMounted(() => {
        fetchFiles(navigation_dir);
    });

    return {
        file_list,
        fetchFiles
    }
}
