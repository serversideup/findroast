import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';

const form = reactive({
    types: [],
    sort: '',
    search: ''
});

const companiesLoading = ref(false);
let watcherActive = false;

export const useCompanies = () => {
    // Only set up the watcher once
    if (!watcherActive) {
        watchDebounced(form, () => {
            loadCompanies();
        }, {
            debounce: 300
        });
        watcherActive = true;
    }

    const loadCompanies = () => {
        companiesLoading.value = true;
        router.visit('/companies', {
            only: ['companies'],
            data: form,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                companiesLoading.value = false;
            },
            onError: () => {
                companiesLoading.value = false;
            }
        });
    };

    return {
        form,
        companiesLoading
    };
};