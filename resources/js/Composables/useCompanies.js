import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';

const form = reactive({
    types: [],
    sort: '',
    search: ''
});

const companiesLoading = ref(false);

export const useCompanies = () => {

    watchDebounced(form, () => {
        loadCompanies();
    }, {
        debounce: 500
    });

    const loadCompanies = () => {
        router.visit('/companies', {
            only: ['companies'],
            data: form,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                companiesLoading.value = false
            }
        })
    }

    return {
        form
    }
}