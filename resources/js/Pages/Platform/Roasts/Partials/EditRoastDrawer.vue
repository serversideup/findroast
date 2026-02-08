<template>
    <Drawer :show="open" @close="close">
        <template #title>
            <div>
                <div class="text-sm font-medium text-stone-500">{{ company }}</div>
                <div class="text-lg font-semibold text-stone-900 mt-0.5">{{ form.name }}</div>
            </div>
        </template>

        <template #content>
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <InputLabel for="name" value="Name" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <TextInput
                        id="name"
                        v-model="form.name"
                        class="w-full"/>
                </div>

                <!-- URL -->
                <div>
                    <InputLabel for="url" value="URL" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <TextInput
                        id="url"
                        v-model="form.url"
                        class="w-full"/>
                    <a
                        v-if="form.url"
                        :href="form.url"
                        class="inline-flex items-center gap-1 text-xs text-amber-700 hover:text-amber-800 mt-1.5"
                        target="_blank">
                        View on company website
                        <ArrowTopRightOnSquareIcon class="h-3 w-3" />
                    </a>
                </div>

                <!-- Price -->
                <div>
                    <InputLabel for="price" value="Price" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <TextInput
                        id="price"
                        v-model="form.price"
                        class="w-full"/>
                </div>

                <!-- Primary Image -->
                <div>
                    <InputLabel for="primary_image" value="Primary Image URL" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <TextInput
                        id="primary_image"
                        v-model="form.new_primary_image"
                        placeholder="https://..."
                        class="w-full"/>

                    <div v-if="form.new_primary_image || form.primary_image" class="mt-3">
                        <img
                            :src="form.new_primary_image || `/storage/${form.primary_image}`"
                            class="w-24 h-24 rounded-lg object-cover border border-stone-200"
                            @error="$event.target.src = ''" />
                    </div>
                    <div v-else class="mt-3 w-24 h-24 rounded-lg bg-stone-100 flex items-center justify-center border border-stone-200">
                        <span class="text-xs text-stone-400">No image</span>
                    </div>
                </div>

                <!-- Flavor Notes -->
                <div>
                    <InputLabel for="flavorNotes" value="Flavor Notes" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <ComboboxRoot
                        v-model="form.flavorNotes"
                        multiple
                        ignore-filter
                        class="w-full relative">
                        <ComboboxAnchor class="w-full inline-flex min-h-[42px] items-center justify-between px-3 py-2 text-sm rounded-lg border border-stone-300 bg-white hover:border-stone-400 focus-within:border-amber-500 focus-within:ring-1 focus-within:ring-amber-500 transition-colors">
                            <TagsInputRoot
                                v-model="form.flavorNotes"
                                delimiter=""
                                class="flex gap-1.5 items-center flex-wrap flex-1">
                                <TagsInputItem
                                    v-for="flavorNote in form.flavor_notes"
                                    :key="flavorNote.id"
                                    :value="flavorNote.name"
                                    class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-medium bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors">
                                    <TagsInputItemText />
                                    <TagsInputItemDelete @click="removeFlavorNote(flavorNote)">
                                        <XMarkIcon class="h-3 w-3" />
                                    </TagsInputItemDelete>
                                </TagsInputItem>

                                <ComboboxInput
                                    v-model="flavorNoteQuery"
                                    as-child
                                    @keydown.escape.stop="flavorNoteQuery = ''">
                                    <TagsInputInput
                                        placeholder="Search flavor notes..."
                                        class="border-none outline-none text-sm flex-1 min-w-[120px] bg-transparent placeholder:text-stone-400 px-0"
                                        @keydown.enter.prevent/>
                                </ComboboxInput>
                            </TagsInputRoot>

                            <ComboboxTrigger>
                                <ChevronDownIcon class="h-4 w-4 text-stone-500 flex-shrink-0 ml-2" />
                            </ComboboxTrigger>
                        </ComboboxAnchor>

                        <ComboboxContent class="absolute z-50 w-full mt-1 bg-white border border-stone-200 rounded-lg shadow-lg max-h-[200px] overflow-hidden">
                            <ComboboxViewport class="p-1">
                                <ComboboxGroup v-if="filteredFlavorNotes.length">
                                    <ComboboxItem
                                        v-for="(flavorNote, index) in filteredFlavorNotes"
                                        :key="index"
                                        class="text-sm text-stone-700 rounded-md flex items-center px-3 py-2 cursor-pointer select-none hover:bg-amber-50 hover:text-amber-900 data-[highlighted]:bg-amber-50 data-[highlighted]:text-amber-900 transition-colors"
                                        :value="flavorNote">
                                        <ComboboxItemIndicator class="mr-2">
                                            <CheckIcon class="h-4 w-4 text-amber-700" />
                                        </ComboboxItemIndicator>
                                        <span>{{ flavorNote.name }}</span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                                <div v-else class="px-3 py-2 text-sm text-stone-500">
                                    No results found
                                </div>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <!-- Countries -->
                <div>
                    <InputLabel for="countries" value="Countries" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <ComboboxRoot
                        v-model="form.countries"
                        multiple
                        ignore-filter
                        class="w-full relative">
                        <ComboboxAnchor class="w-full inline-flex min-h-[42px] items-center justify-between px-3 py-2 text-sm rounded-lg border border-stone-300 bg-white hover:border-stone-400 focus-within:border-amber-500 focus-within:ring-1 focus-within:ring-amber-500 transition-colors">
                            <TagsInputRoot
                                v-model="form.countries"
                                delimiter=""
                                class="flex gap-1.5 items-center flex-wrap flex-1">
                                <TagsInputItem
                                    v-for="country in form.countries"
                                    :key="country.id"
                                    :value="country.name"
                                    class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-medium bg-stone-100 text-stone-800 hover:bg-stone-200 transition-colors">
                                    <TagsInputItemText />
                                    <TagsInputItemDelete @click="removeCountry(country)">
                                        <XMarkIcon class="h-3 w-3" />
                                    </TagsInputItemDelete>
                                </TagsInputItem>

                                <ComboboxInput
                                    v-model="countryQuery"
                                    as-child
                                    @keydown.escape.stop="countryQuery = ''">
                                    <TagsInputInput
                                        placeholder="Search countries..."
                                        class="border-none outline-none text-sm flex-1 min-w-[120px] bg-transparent placeholder:text-stone-400 px-0"
                                        @keydown.enter.prevent/>
                                </ComboboxInput>
                            </TagsInputRoot>

                            <ComboboxTrigger>
                                <ChevronDownIcon class="h-4 w-4 text-stone-500 flex-shrink-0 ml-2" />
                            </ComboboxTrigger>
                        </ComboboxAnchor>

                        <ComboboxContent class="absolute z-50 w-full mt-1 bg-white border border-stone-200 rounded-lg shadow-lg max-h-[200px] overflow-hidden">
                            <ComboboxViewport class="p-1">
                                <ComboboxGroup v-if="filteredCountries.length">
                                    <ComboboxItem
                                        v-for="(country, index) in filteredCountries"
                                        :key="index"
                                        class="text-sm text-stone-700 rounded-md flex items-center px-3 py-2 cursor-pointer select-none hover:bg-amber-50 hover:text-amber-900 data-[highlighted]:bg-amber-50 data-[highlighted]:text-amber-900 transition-colors"
                                        :value="country">
                                        <ComboboxItemIndicator class="mr-2">
                                            <CheckIcon class="h-4 w-4 text-amber-700" />
                                        </ComboboxItemIndicator>
                                        <span>{{ country.name }}</span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                                <div v-else class="px-3 py-2 text-sm text-stone-500">
                                    No results found
                                </div>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <!-- Processes -->
                <div>
                    <InputLabel for="processes" value="Processes" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <ComboboxRoot
                        v-model="form.processes"
                        multiple
                        ignore-filter
                        class="w-full relative">
                        <ComboboxAnchor class="w-full inline-flex min-h-[42px] items-center justify-between px-3 py-2 text-sm rounded-lg border border-stone-300 bg-white hover:border-stone-400 focus-within:border-amber-500 focus-within:ring-1 focus-within:ring-amber-500 transition-colors">
                            <TagsInputRoot
                                v-model="form.processes"
                                delimiter=""
                                class="flex gap-1.5 items-center flex-wrap flex-1">
                                <TagsInputItem
                                    v-for="process in form.processes"
                                    :key="process.id"
                                    :value="process.name"
                                    class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-medium bg-stone-100 text-stone-800 hover:bg-stone-200 transition-colors">
                                    <TagsInputItemText />
                                    <TagsInputItemDelete @click="removeProcess(process)">
                                        <XMarkIcon class="h-3 w-3" />
                                    </TagsInputItemDelete>
                                </TagsInputItem>

                                <ComboboxInput
                                    v-model="processQuery"
                                    as-child
                                    @keydown.escape.stop="processQuery = ''">
                                    <TagsInputInput
                                        placeholder="Search processes..."
                                        class="border-none outline-none text-sm flex-1 min-w-[120px] bg-transparent placeholder:text-stone-400 px-0"
                                        @keydown.enter.prevent/>
                                </ComboboxInput>
                            </TagsInputRoot>

                            <ComboboxTrigger>
                                <ChevronDownIcon class="h-4 w-4 text-stone-500 flex-shrink-0 ml-2" />
                            </ComboboxTrigger>
                        </ComboboxAnchor>

                        <ComboboxContent class="absolute z-50 w-full mt-1 bg-white border border-stone-200 rounded-lg shadow-lg max-h-[200px] overflow-hidden">
                            <ComboboxViewport class="p-1">
                                <ComboboxGroup v-if="filteredProcesses.length">
                                    <ComboboxItem
                                        v-for="(process, index) in filteredProcesses"
                                        :key="index"
                                        class="text-sm text-stone-700 rounded-md flex items-center px-3 py-2 cursor-pointer select-none hover:bg-amber-50 hover:text-amber-900 data-[highlighted]:bg-amber-50 data-[highlighted]:text-amber-900 transition-colors"
                                        :value="process">
                                        <ComboboxItemIndicator class="mr-2">
                                            <CheckIcon class="h-4 w-4 text-amber-700" />
                                        </ComboboxItemIndicator>
                                        <span>{{ process.name }}</span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                                <div v-else class="px-3 py-2 text-sm text-stone-500">
                                    No results found
                                </div>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <!-- Elevations -->
                <div>
                    <InputLabel for="elevations" value="Elevations" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <ComboboxRoot
                        v-model="form.elevations"
                        multiple
                        ignore-filter
                        class="w-full relative">
                        <ComboboxAnchor class="w-full inline-flex min-h-[42px] items-center justify-between px-3 py-2 text-sm rounded-lg border border-stone-300 bg-white hover:border-stone-400 focus-within:border-amber-500 focus-within:ring-1 focus-within:ring-amber-500 transition-colors">
                            <TagsInputRoot
                                v-model="form.elevations"
                                delimiter=""
                                class="flex gap-1.5 items-center flex-wrap flex-1">
                                <TagsInputItem
                                    v-for="elevation in form.elevations"
                                    :key="elevation.id"
                                    :value="elevation.name"
                                    class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-medium bg-stone-100 text-stone-800 hover:bg-stone-200 transition-colors">
                                    <TagsInputItemText />
                                    <TagsInputItemDelete @click="removeElevation(elevation)">
                                        <XMarkIcon class="h-3 w-3" />
                                    </TagsInputItemDelete>
                                </TagsInputItem>

                                <ComboboxInput
                                    v-model="elevationQuery"
                                    as-child
                                    @keydown.escape.stop="elevationQuery = ''">
                                    <TagsInputInput
                                        placeholder="Search elevations..."
                                        class="border-none outline-none text-sm flex-1 min-w-[120px] bg-transparent placeholder:text-stone-400 px-0"
                                        @keydown.enter.prevent/>
                                </ComboboxInput>
                            </TagsInputRoot>

                            <ComboboxTrigger>
                                <ChevronDownIcon class="h-4 w-4 text-stone-500 flex-shrink-0 ml-2" />
                            </ComboboxTrigger>
                        </ComboboxAnchor>

                        <ComboboxContent class="absolute z-50 w-full mt-1 bg-white border border-stone-200 rounded-lg shadow-lg max-h-[200px] overflow-hidden">
                            <ComboboxViewport class="p-1">
                                <ComboboxGroup v-if="filteredElevations.length">
                                    <ComboboxItem
                                        v-for="(elevation, index) in filteredElevations"
                                        :key="index"
                                        class="text-sm text-stone-700 rounded-md flex items-center px-3 py-2 cursor-pointer select-none hover:bg-amber-50 hover:text-amber-900 data-[highlighted]:bg-amber-50 data-[highlighted]:text-amber-900 transition-colors"
                                        :value="elevation">
                                        <ComboboxItemIndicator class="mr-2">
                                            <CheckIcon class="h-4 w-4 text-amber-700" />
                                        </ComboboxItemIndicator>
                                        <span>{{ elevation.name }}</span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                                <div v-else class="px-3 py-2 text-sm text-stone-500">
                                    No results found
                                </div>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <!-- Varieties -->
                <div>
                    <InputLabel for="varieties" value="Varieties" class="text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5"/>
                    <ComboboxRoot
                        v-model="form.varieties"
                        multiple
                        ignore-filter
                        class="w-full relative">
                        <ComboboxAnchor class="w-full inline-flex min-h-[42px] items-center justify-between px-3 py-2 text-sm rounded-lg border border-stone-300 bg-white hover:border-stone-400 focus-within:border-amber-500 focus-within:ring-1 focus-within:ring-amber-500 transition-colors">
                            <TagsInputRoot
                                v-model="form.varieties"
                                delimiter=""
                                class="flex gap-1.5 items-center flex-wrap flex-1">
                                <TagsInputItem
                                    v-for="variety in form.varieties"
                                    :key="variety.id"
                                    :value="variety.name"
                                    class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-medium bg-stone-100 text-stone-800 hover:bg-stone-200 transition-colors">
                                    <TagsInputItemText />
                                    <TagsInputItemDelete @click="removeVariety(variety)">
                                        <XMarkIcon class="h-3 w-3" />
                                    </TagsInputItemDelete>
                                </TagsInputItem>

                                <ComboboxInput
                                    v-model="varietyQuery"
                                    as-child
                                    @keydown.escape.stop="varietyQuery = ''">
                                    <TagsInputInput
                                        placeholder="Search varieties..."
                                        class="border-none outline-none text-sm flex-1 min-w-[120px] bg-transparent placeholder:text-stone-400 px-0"
                                        @keydown.enter.prevent/>
                                </ComboboxInput>
                            </TagsInputRoot>

                            <ComboboxTrigger>
                                <ChevronDownIcon class="h-4 w-4 text-stone-500 flex-shrink-0 ml-2" />
                            </ComboboxTrigger>
                        </ComboboxAnchor>

                        <ComboboxContent class="absolute z-50 w-full mt-1 bg-white border border-stone-200 rounded-lg shadow-lg max-h-[200px] overflow-hidden">
                            <ComboboxViewport class="p-1">
                                <ComboboxGroup v-if="filteredVarieties.length">
                                    <ComboboxItem
                                        v-for="(variety, index) in filteredVarieties"
                                        :key="index"
                                        class="text-sm text-stone-700 rounded-md flex items-center px-3 py-2 cursor-pointer select-none hover:bg-amber-50 hover:text-amber-900 data-[highlighted]:bg-amber-50 data-[highlighted]:text-amber-900 transition-colors"
                                        :value="variety">
                                        <ComboboxItemIndicator class="mr-2">
                                            <CheckIcon class="h-4 w-4 text-amber-700" />
                                        </ComboboxItemIndicator>
                                        <span>{{ variety.name }}</span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                                <div v-else class="px-3 py-2 text-sm text-stone-500">
                                    No results found
                                </div>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="flex items-center justify-between px-6 py-4 border-t border-stone-200 bg-stone-50">
                <button
                    @click="deleteRoast()"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                    Delete Roast
                </button>
                <button
                    @click="updateRoast()"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-700 hover:bg-amber-800 rounded-lg transition-colors">
                    Save Changes
                </button>
            </div>
        </template>
    </Drawer>
</template>

<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Drawer from '@/Components/Drawer.vue';
import { ref, computed } from 'vue';
import { useEventBus } from '@vueuse/core';
import { router, usePage } from '@inertiajs/vue3';
import { ArrowTopRightOnSquareIcon } from '@heroicons/vue/24/outline';

import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxRoot,
    ComboboxTrigger,
    ComboboxViewport,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
    TagsInputRoot,
    useFilter
} from 'reka-ui';
import { XMarkIcon, ChevronDownIcon, CheckIcon } from '@heroicons/vue/24/outline';

import { useRoast } from '@/Composables/useRoast';

const open = ref(false);
const company = ref('');

const {
    form,
    setRoast
} = useRoast();

const promptBus = useEventBus('roast-prompt-event-bus');
const notificationBus = useEventBus('roast-notification');

const listener = (event, data) => {
    if (event === 'prompt-edit-roast') {
        company.value = data.company.name;
        setRoast(data);
        open.value = true;
    }
}

promptBus.on(listener);

const close = () => {
    open.value = false;
    form.reset();
}

const deleteRoast = () => {
    router.delete(route('platform.roasts.delete', { roast: form.id }), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Roast deleted'
            });
            close();
        }
    });
}

const updateRoast = () => {
    form.put(route('platform.roasts.update', { roast: form.id }), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Roast updated'
            });
            close();
        }
    });
}

const { contains } = useFilter({ sensitivity: 'base' });

// Countries
const countries = computed(() => usePage().props.countries);
const countryQuery = ref('');
const filteredCountries = computed(() => countries.value.filter(country => contains(country.name, countryQuery.value)));
const removeCountry = (country) => {
    form.countries = form.countries.filter(c => c.id !== country.id);
}

// Processes
const processes = computed(() => usePage().props.processes);
const processQuery = ref('');
const filteredProcesses = computed(() => processes.value.filter(process => contains(process.name, processQuery.value)));
const removeProcess = (process) => {
    form.processes = form.processes.filter(p => p.id !== process.id);
}

// Varieties
const varieties = computed(() => usePage().props.varieties);
const varietyQuery = ref('');
const filteredVarieties = computed(() => varieties.value.filter(variety => contains(variety.name, varietyQuery.value)));
const removeVariety = (variety) => {
    form.varieties = form.varieties.filter(v => v.id !== variety.id);
}

// Elevations
const elevations = computed(() => usePage().props.elevations);
const elevationQuery = ref('');
const filteredElevations = computed(() => elevations.value.filter(elevation => contains(elevation.name, elevationQuery.value)));
const removeElevation = (elevation) => {
    form.elevations = form.elevations.filter(e => e.id !== elevation.id);
}

// Flavor Notes
const flavorNotes = computed(() => usePage().props.flavorNotes);
const flavorNoteQuery = ref('');
const filteredFlavorNotes = computed(() => flavorNotes.value.filter(flavorNote => contains(flavorNote.name, flavorNoteQuery.value)));
const removeFlavorNote = (flavorNote) => {
    form.flavorNotes = form.flavorNotes.filter(f => f.id !== flavorNote.id);
}
</script>
