<template>
    <div v-if="hasActiveFilters" class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 mb-4">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <!-- Active Filters Section -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-medium text-amber-800">Active filters:</span>
                
                <!-- Filter chips -->
                <span 
                    v-for="filter in activeFilters" 
                    :key="filter.type + '-' + filter.id"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-white border border-amber-300 text-amber-900">
                    <span class="text-amber-500">{{ filter.icon }}</span>
                    {{ filter.label }}
                    <button 
                        type="button" 
                        @click="removeFilter(filter.type, filter.id)"
                        class="ml-0.5 hover:text-amber-600">
                        <XMarkIcon class="h-3.5 w-3.5" />
                    </button>
                </span>

                <button 
                    type="button" 
                    @click="clearAllFilters"
                    class="text-xs text-amber-700 hover:text-amber-900 underline">
                    Clear all
                </button>
            </div>

            <!-- Subscribe Prompt -->
            <div class="flex items-center">
                <button 
                    type="button"
                    @click="showSubscribeModal = true"
                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-md transition-colors">
                    <span>🔔</span>
                    Get notified of new matches
                </button>
            </div>
        </div>

        <!-- Subscribe Modal -->
        <Modal :show="showSubscribeModal" max-width="md" @close="showSubscribeModal = false">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl">☕️</span>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Never miss your perfect roast</h3>
                        <p class="text-sm text-gray-500">Get email alerts when new coffees match your filters</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                    <p class="text-xs text-gray-500 mb-2">You'll be notified when new coffees match:</p>
                    <div class="flex flex-wrap gap-1">
                        <span 
                            v-for="filter in activeFilters" 
                            :key="'modal-' + filter.type + '-' + filter.id"
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-800">
                            {{ filter.icon }} {{ filter.label }}
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
                        <div class="flex items-center gap-2">
                            <input 
                                id="frequency-instant" 
                                type="radio" 
                                value="instant" 
                                v-model="subscribeForm.frequency"
                                class="h-4 w-4 border-gray-300 text-amber-600 focus:ring-amber-500" />
                            <label for="frequency-instant" class="text-sm text-gray-700">Instant alerts</label>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <input 
                                id="frequency-daily" 
                                type="radio" 
                                value="daily" 
                                v-model="subscribeForm.frequency"
                                class="h-4 w-4 border-gray-300 text-amber-600 focus:ring-amber-500" />
                            <label for="frequency-daily" class="text-sm text-gray-700">Daily digest</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button 
                            type="button" 
                            @click="showSubscribeModal = false"
                            class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900">
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            :disabled="subscribeForm.processing"
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-md transition-colors disabled:opacity-50">
                            🔔 Subscribe
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
import { XMarkIcon } from '@heroicons/vue/24/outline';
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
            filters.push({ type: 'companies', id, label: company.name, icon: '🏢' });
        }
    });

    form.processes.forEach(id => {
        const process = props.processes?.find(p => p.id === id);
        if (process) {
            filters.push({ type: 'processes', id, label: process.name, icon: '⚙️' });
        }
    });

    form.flavor_notes.forEach(id => {
        const note = props.flavorNotes?.find(n => n.id === id);
        if (note) {
            filters.push({ type: 'flavor_notes', id, label: note.name, icon: '🍫' });
        }
    });

    form.varieties.forEach(id => {
        const variety = props.varieties?.find(v => v.id === id);
        if (variety) {
            filters.push({ type: 'varieties', id, label: variety.name, icon: '🌱' });
        }
    });

    form.countries.forEach(id => {
        const country = props.countries?.find(c => c.id === id);
        if (country) {
            filters.push({ type: 'countries', id, label: country.name, icon: '🌍' });
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

