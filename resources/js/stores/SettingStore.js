import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";


export const useSettingStore = defineStore('SettingStore', () => {
    const setting = ref({
        approval1: '',
        approval2: '',
        approval3: '',
     
    });

   

    const getSetting = async () => {
        await axios.get('/api/getSettings')
            .then((response) => {
                setting.value = response.data;
            });
    };

    return { setting, getSetting };
});
