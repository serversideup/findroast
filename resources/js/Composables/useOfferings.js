import { reactive, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const form = reactive({
    search: '',
    processes: [],
    origins: [],
    flavor_notes: [],
    varieties: [],
    elevations: [],
    countries: [],
    companies: []
});

const offeringsLoading = ref(false);
let debounceTimer = null;
let watcherActive = false;

const loadRoasts = () => {
    offeringsLoading.value = true;
    router.visit('/', {
        only: ['roasts'],
        data: form,
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            offeringsLoading.value = false;
        }
    });
};

export const useOfferings = () => {
    if (!watcherActive) {
        watcherActive = true;
        watch(form, () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(loadRoasts, 300);
        });
    }

    return {
        form,
        offeringsLoading
    };
};
