import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";
import { useStorage } from '@vueuse/core';

export const useNotiStore = defineStore('NotiStore', () => {
    const messagecount = ref();




    const getCount = async () => {
        await axios.get('/api/getcounts')
            .then((response) => {
                messagecount.value = response.data;
            });
    };

    return { messagecount, getCount };
});
