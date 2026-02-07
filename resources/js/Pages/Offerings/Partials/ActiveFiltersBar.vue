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
            @click="showSubscribeModal = true"
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
                    <p class="text-xs text-stone-500 mb-2">You'll be notified when new coffees match:</p>
                    <div class="flex flex-wrap gap-1">
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
                        <InputLabel for="subscribe-email" value="Email address" />
                        <TextInput
                            id="subscribe-email"
                            type="email"
                            v-model="subscribeForm.email"
                            placeholder="you@example.com"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError :message="subscribeForm.errors.email" class="mt-1" />
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                value="instant"
                                v-model="subscribeForm.frequency"
                                class="h-4 w-4 border-stone-300 text-amber-700 focus:ring-amber-600"
                            />
                            <span class="text-sm text-stone-700">Instant alerts</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                value="daily"
                                v-model="subscribeForm.frequency"
                                class="h-4 w-4 border-stone-300 text-amber-700 focus:ring-amber-600"
                            />
                            <span class="text-sm text-stone-700">Daily digest</span>
                        </label>
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
import { XMarkIcon, BellIcon } from '@heroicons/vue/20/solid';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const { form } = useOfferings();
const showSubscribeModal = ref(false);

const subscribeForm = useForm({
    email: '',
    frequency: 'instant',
    filters: {}
});

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

    subscribeForm.post(route('subscriptions.store'), {
        onSuccess: () => {
            showSubscribeModal.value = false;
            subscribeForm.reset();
        }
    });
};
</script>
