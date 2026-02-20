<template>
    <div v-if="hasActiveFilters" class="flex items-center gap-2 flex-wrap py-2.5 border-b border-stone-100">
        <span class="text-xs text-stone-400 flex-shrink-0">Filtered by</span>

        <!-- Filter chips -->
        <span
            v-for="filter in activeFilters"
            :key="filter.type + '-' + filter.id"
            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-amber-50 border border-amber-200 text-amber-800"
        >
            {{ filter.label }}
            <button
                type="button"
                @click="removeFilter(filter.type, filter.id)"
                class="hover:text-amber-600 ml-0.5"
            >
                <XMarkIcon class="h-3 w-3" />
            </button>
        </span>

        <button
            type="button"
            @click="clearAllFilters"
            class="text-xs text-stone-400 hover:text-stone-600 underline underline-offset-2 flex-shrink-0"
        >
            Clear all
        </button>

        <!-- Subscribe button -->
        <button
            type="button"
            @click="handleGetNotified"
            class="ml-auto text-xs font-medium text-amber-700 hover:text-amber-800 flex items-center gap-1 flex-shrink-0"
        >
            <BellIcon class="h-3.5 w-3.5" />
            Get notified
        </button>

        <!-- Subscribe Modal -->
        <Modal :show="showSubscribeModal" max-width="md" @close="showSubscribeModal = false">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <BellIcon class="h-5 w-5 text-amber-700" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-stone-900">Never miss your perfect roast</h3>
                        <p class="text-sm text-stone-500">Get email alerts when new coffees match your filters</p>
                    </div>
                </div>

                <div class="bg-stone-50 rounded-lg p-3 mb-4">
                    <p class="text-xs text-stone-500 mb-2">You'll receive a daily digest when new coffees match:</p>
                    <div class="flex flex-wrap gap-1">
                        <span
                            v-if="form.search"
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-800"
                        >
                            Search: "{{ form.search }}"
                        </span>
                        <span
                            v-for="filter in activeFilters"
                            :key="'modal-' + filter.type + '-' + filter.id"
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-800"
                        >
                            {{ filter.label }}
                        </span>
                    </div>
                </div>

                <form @submit.prevent="subscribeToFilters">
                    <div class="mb-4">
                        <InputLabel value="Notifications will be sent to" />
                        <div class="mt-1 px-3 py-2 bg-stone-100 border border-stone-200 rounded-lg text-sm text-stone-700">
                            {{ $page.props.auth.user.email }}
                        </div>
                        <p class="text-xs text-stone-500 mt-1">Daily digest emails will be sent to your account email</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="showSubscribeModal = false"
                            class="px-4 py-2 text-sm text-stone-600 hover:text-stone-800"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="subscribeForm.processing"
                            class="px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50"
                        >
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import { useOfferings } from '@/Composables/useOfferings';
import { useEventBus } from '@vueuse/core';
import { XMarkIcon, BellIcon } from '@heroicons/vue/20/solid';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const { form } = useOfferings();
const showSubscribeModal = ref(false);
const promptBus = useEventBus('roast-prompt-bus');

const subscribeForm = useForm({
    filters: {},
    search: ''
});

const handleGetNotified = () => {
    // Check if user is authenticated
    if (!usePage().props.auth.user) {
        // Trigger login modal
        promptBus.emit('prompt-login');
        return;
    }

    showSubscribeModal.value = true;
};

const activeFilters = computed(() => {
    const filters = [];
    const props = usePage().props;

    form.companies.forEach(id => {
        const company = props.companies?.find(c => c.id === id);
        if (company) {
            filters.push({ type: 'companies', id, label: company.name });
        }
    });

    form.processes.forEach(id => {
        const process = props.processes?.find(p => p.id === id);
        if (process) {
            filters.push({ type: 'processes', id, label: process.name });
        }
    });

    form.flavor_notes.forEach(id => {
        const note = props.flavorNotes?.find(n => n.id === id);
        if (note) {
            filters.push({ type: 'flavor_notes', id, label: note.name });
        }
    });

    form.varieties.forEach(id => {
        const variety = props.varieties?.find(v => v.id === id);
        if (variety) {
            filters.push({ type: 'varieties', id, label: variety.name });
        }
    });

    form.countries.forEach(id => {
        const country = props.countries?.find(c => c.id === id);
        if (country) {
            filters.push({ type: 'countries', id, label: country.name });
        }
    });

    return filters;
});

const hasActiveFilters = computed(() => activeFilters.value.length > 0);

const removeFilter = (type, id) => {
    const index = form[type].indexOf(id);
    if (index > -1) {
        form[type].splice(index, 1);
    }
};

const clearAllFilters = () => {
    form.companies = [];
    form.processes = [];
    form.flavor_notes = [];
    form.varieties = [];
    form.countries = [];
};

const subscribeToFilters = () => {
    subscribeForm.filters = {
        companies: [...form.companies],
        processes: [...form.processes],
        flavor_notes: [...form.flavor_notes],
        varieties: [...form.varieties],
        countries: [...form.countries]
    };
    subscribeForm.search = form.search;

    subscribeForm.post(route('subscriptions.store'), {
        onSuccess: () => {
            showSubscribeModal.value = false;
            subscribeForm.reset();
        }
    });
};
</script>
