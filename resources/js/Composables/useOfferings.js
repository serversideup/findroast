import { reactive, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const form = reactive({
    processes: [],
    origins: [],
    flavor_notes: [],
    varieties: [],
    elevations: [],
    countries: [],
    companies: []
});

const offeringsLoading = ref(false);

export const useOfferings = () => {

    watch(form, () => {
        loadRoasts();
    });

    const loadRoasts = () => {
        router.visit('/offerings', {
            only: ['roasts'],
            data: form,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                offeringsLoading.value = false
            }
        })
    }

    return {
        form
    }
}