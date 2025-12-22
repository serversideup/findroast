<template>
    <Drawer :show="open" @close="close">
        <template #title>
            {{ company }}:<br>{{ form.name }}
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-3">
                <div class="w-full flex flex-col">
                    <InputLabel for="name" value="Name"/>
                    <TextInput 
                        id="name"
                        v-model="form.name"/>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="url" value="URL"/>
                    <TextInput 
                        id="url"
                        v-model="form.url"/>
                    <a :href="form.url" class="underline" target="_blank">View on company website &rarr;</a>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="price" value="Price"/>
                    <TextInput
                        id="price"
                        v-model="form.price"/>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="primary_image" value="Primary Image"/>
                    <TextInput 
                        id="primary_image"
                        v-model="form.new_primary_image"/>
                    <img class="mt-1" v-if="form.primary_image != null && form.new_primary_image == ''" :src="'/storage/'+form.primary_image"/>
                    <img class="mt-1" v-if="form.new_primary_image != ''" :src="form.new_primary_image"/>
                    <div class="w-full flex flex-col" v-if="form.new_primary_image == '' && form.primary_image == null">
                        <p class="text-sm text-gray-500">No primary image set</p>
                    </div>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="varieties" value="Varieties"/>
                    <ComboboxRoot
                        v-model="form.varieties"
                        multiple
                        ignore-filter
                        class="w-full mx-auto relative border border-gray-300 rounded-md shadow-sm">
                            <ComboboxAnchor class="w-full inline-flex items-center justify-between p-2 text-[13px] rounded-md leading-none gap-[5px] bg-white  focus:shadow-black outline-none">
                                <TagsInputRoot
                                    v-model="form.varieties"
                                    delimiter=""
                                    class="flex gap-2 items-center rounded-lg flex-wrap">
                                        <TagsInputItem
                                            v-for="variety in form.varieties"
                                            :key="variety.id"
                                            :value="variety.name"
                                            class="flex items-center justify-center gap-2 text-white bg-gray-800 aria-[current=true]:bg-grass9 rounded px-2 py-1">
                                                <TagsInputItemText class="text-sm" />
                                                <TagsInputItemDelete @click="removeVariety(variety)">
                                                    <XMarkIcon class="h-4 w-4" />
                                                </TagsInputItemDelete>
                                        </TagsInputItem>

                                        <ComboboxInput
                                            v-model="varietyQuery"
                                            as-child
                                            @keydown.escape.stop="varietyQuery = ''">
                                            <TagsInputInput
                                                placeholder="Varieties..."
                                                class="border-none outline-none ring-0 focus:ring-0 focus:outline-none flex-1 rounded bg-transparent placeholder:text-gray-500 px-1"
                                                @keydown.enter.prevent/>
                                        </ComboboxInput>
                                </TagsInputRoot>

                                <ComboboxTrigger>
                                    <ChevronDownIcon class="h-4 w-4 text-gray-500" />
                                </ComboboxTrigger>
                            </ComboboxAnchor>

                        <ComboboxContent class="absolute z-10 w-full mt-2 bg-white overflow-hidden rounded shadow-[0px_10px_38px_-10px_rgba(22,_23,_24,_0.35),_0px_10px_20px_-15px_rgba(22,_23,_24,_0.2)] will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade">
                            <ComboboxViewport class="p-[5px]">
                                <ComboboxGroup v-if="filteredVarieties.length">
                                    <ComboboxLabel class="px-[25px] text-xs leading-[25px] text-mauve11">
                                        Varieties
                                    </ComboboxLabel>

                                    <ComboboxItem
                                        v-for="(variety, index) in filteredVarieties"
                                        :key="index"
                                        class="text-[13px] leading-none text-grass11 rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-mauve8 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:bg-gray-800 data-[highlighted]:text-white"
                                        :value="variety">
                                            <ComboboxItemIndicator
                                                class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                                                <CheckIcon class="h-4 w-4 text-white" />
                                            </ComboboxItemIndicator>
                                            <span>
                                                {{ variety.name }}
                                            </span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="varieties" value="Varieties"/>
                    <ComboboxRoot
                        v-model="form.varieties"
                        multiple
                        ignore-filter
                        class="w-full mx-auto relative border border-gray-300 rounded-md shadow-sm">
                            <ComboboxAnchor class="w-full inline-flex items-center justify-between p-2 text-[13px] rounded-md leading-none gap-[5px] bg-white  focus:shadow-black outline-none">
                                <TagsInputRoot
                                    v-model="form.varieties"
                                    delimiter=""
                                    class="flex gap-2 items-center rounded-lg flex-wrap">
                                        <TagsInputItem
                                            v-for="variety in form.varieties"
                                            :key="variety.id"
                                            :value="variety.name"
                                            class="flex items-center justify-center gap-2 text-white bg-gray-800 aria-[current=true]:bg-grass9 rounded px-2 py-1">
                                                <TagsInputItemText class="text-sm" />
                                                <TagsInputItemDelete @click="removeVariety(variety)">
                                                    <XMarkIcon class="h-4 w-4" />
                                                </TagsInputItemDelete>
                                        </TagsInputItem>

                                        <ComboboxInput
                                            v-model="varietyQuery"
                                            as-child
                                            @keydown.escape.stop="varietyQuery = ''">
                                            <TagsInputInput
                                                placeholder="Varieties..."
                                                class="border-none outline-none ring-0 focus:ring-0 focus:outline-none flex-1 rounded bg-transparent placeholder:text-gray-500 px-1"
                                                @keydown.enter.prevent/>
                                        </ComboboxInput>
                                </TagsInputRoot>

                                <ComboboxTrigger>
                                    <ChevronDownIcon class="h-4 w-4 text-gray-500" />
                                </ComboboxTrigger>
                            </ComboboxAnchor>

                        <ComboboxContent class="absolute z-10 w-full mt-2 bg-white overflow-hidden rounded shadow-[0px_10px_38px_-10px_rgba(22,_23,_24,_0.35),_0px_10px_20px_-15px_rgba(22,_23,_24,_0.2)] will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade">
                            <ComboboxViewport class="p-[5px]">
                                <ComboboxGroup v-if="filteredVarieties.length">
                                    <ComboboxLabel class="px-[25px] text-xs leading-[25px] text-mauve11">
                                        Varieties
                                    </ComboboxLabel>

                                    <ComboboxItem
                                        v-for="(variety, index) in filteredVarieties"
                                        :key="index"
                                        class="text-[13px] leading-none text-grass11 rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-mauve8 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:bg-gray-800 data-[highlighted]:text-white"
                                        :value="variety">
                                            <ComboboxItemIndicator
                                                class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                                                <CheckIcon class="h-4 w-4 text-white" />
                                            </ComboboxItemIndicator>
                                            <span>
                                                {{ variety.name }}
                                            </span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="processes" value="Processes"/>
                    <ComboboxRoot
                        v-model="form.processes"
                        multiple
                        ignore-filter
                        class="w-full mx-auto relative border border-gray-300 rounded-md shadow-sm">
                            <ComboboxAnchor class="w-full inline-flex items-center justify-between p-2 text-[13px] rounded-md leading-none gap-[5px] bg-white  focus:shadow-black outline-none">
                                <TagsInputRoot
                                    v-model="form.processes"
                                    delimiter=""
                                    class="flex gap-2 items-center rounded-lg flex-wrap">
                                        <TagsInputItem
                                            v-for="process in form.processes"
                                            :key="process.id"
                                            :value="process.name"
                                            class="flex items-center justify-center gap-2 text-white bg-gray-800 aria-[current=true]:bg-grass9 rounded px-2 py-1">
                                                <TagsInputItemText class="text-sm" />
                                                <TagsInputItemDelete @click="removeProcess(process)">
                                                    <XMarkIcon class="h-4 w-4" />
                                                </TagsInputItemDelete>
                                        </TagsInputItem>

                                        <ComboboxInput
                                            v-model="processQuery"
                                            as-child
                                            @keydown.escape.stop="processQuery = ''">
                                            <TagsInputInput
                                                placeholder="Processes..."
                                                class="border-none outline-none ring-0 focus:ring-0 focus:outline-none flex-1 rounded bg-transparent placeholder:text-gray-500 px-1"
                                                @keydown.enter.prevent/>
                                        </ComboboxInput>
                                </TagsInputRoot>

                                <ComboboxTrigger>
                                    <ChevronDownIcon class="h-4 w-4 text-gray-500" />
                                </ComboboxTrigger>
                            </ComboboxAnchor>

                        <ComboboxContent class="absolute z-10 w-full mt-2 bg-white overflow-hidden rounded shadow-[0px_10px_38px_-10px_rgba(22,_23,_24,_0.35),_0px_10px_20px_-15px_rgba(22,_23,_24,_0.2)] will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade">
                            <ComboboxViewport class="p-[5px]">
                                <ComboboxGroup v-if="filteredProcesses.length">
                                    <ComboboxLabel class="px-[25px] text-xs leading-[25px] text-mauve11">
                                        Processes
                                    </ComboboxLabel>

                                    <ComboboxItem
                                        v-for="(process, index) in filteredProcesses"
                                        :key="index"
                                        class="text-[13px] leading-none text-grass11 rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-mauve8 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:bg-gray-800 data-[highlighted]:text-white"
                                        :value="process">
                                            <ComboboxItemIndicator
                                                class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                                                <CheckIcon class="h-4 w-4 text-white" />
                                            </ComboboxItemIndicator>
                                            <span>
                                                {{ process.name }}
                                            </span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="countries" value="Countries"/>
                    <ComboboxRoot
                        v-model="form.countries"
                        multiple
                        ignore-filter
                        class="w-full mx-auto relative border border-gray-300 rounded-md shadow-sm">
                            <ComboboxAnchor class="w-full inline-flex items-center justify-between p-2 text-[13px] rounded-md leading-none gap-[5px] bg-white  focus:shadow-black outline-none">
                                <TagsInputRoot
                                    v-model="form.countries"
                                    delimiter=""
                                    class="flex gap-2 items-center rounded-lg flex-wrap">
                                        <TagsInputItem
                                            v-for="country in form.countries"
                                            :key="country.id"
                                            :value="country.name"
                                            class="flex items-center justify-center gap-2 text-white bg-gray-800 aria-[current=true]:bg-grass9 rounded px-2 py-1">
                                                <TagsInputItemText class="text-sm" />
                                                <TagsInputItemDelete @click="removeCountry(country)">
                                                    <XMarkIcon class="h-4 w-4" />
                                                </TagsInputItemDelete>
                                        </TagsInputItem>

                                        <ComboboxInput
                                            v-model="countryQuery"
                                            as-child
                                            @keydown.escape.stop="countryQuery = ''">
                                            <TagsInputInput
                                                placeholder="Countries..."
                                                class="border-none outline-none ring-0 focus:ring-0 focus:outline-none flex-1 rounded bg-transparent placeholder:text-gray-500 px-1"
                                                @keydown.enter.prevent/>
                                        </ComboboxInput>
                                </TagsInputRoot>

                                <ComboboxTrigger>
                                    <ChevronDownIcon class="h-4 w-4 text-gray-500" />
                                </ComboboxTrigger>
                            </ComboboxAnchor>

                        <ComboboxContent class="absolute z-10 w-full mt-2 bg-white overflow-hidden rounded shadow-[0px_10px_38px_-10px_rgba(22,_23,_24,_0.35),_0px_10px_20px_-15px_rgba(22,_23,_24,_0.2)] will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade">
                            <ComboboxViewport class="p-[5px]">
                                <ComboboxGroup v-if="filteredCountries.length">
                                    <ComboboxLabel class="px-[25px] text-xs leading-[25px] text-mauve11">
                                        Countries
                                    </ComboboxLabel>

                                    <ComboboxItem
                                        v-for="(country, index) in filteredCountries"
                                        :key="index"
                                        class="text-[13px] leading-none text-grass11 rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-mauve8 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:bg-gray-800 data-[highlighted]:text-white"
                                        :value="country">
                                            <ComboboxItemIndicator
                                                class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                                                <CheckIcon class="h-4 w-4 text-white" />
                                            </ComboboxItemIndicator>
                                            <span>
                                                {{ country.name }}
                                            </span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="elevations" value="Elevations"/>
                    <ComboboxRoot
                        v-model="form.elevations"
                        multiple
                        ignore-filter
                        class="w-full mx-auto relative border border-gray-300 rounded-md shadow-sm">
                            <ComboboxAnchor class="w-full inline-flex items-center justify-between p-2 text-[13px] rounded-md leading-none gap-[5px] bg-white  focus:shadow-black outline-none">
                                <TagsInputRoot
                                    v-model="form.elevations"
                                    delimiter=""
                                    class="flex gap-2 items-center rounded-lg flex-wrap">
                                        <TagsInputItem
                                            v-for="elevation in form.elevations"
                                            :key="elevation.id"
                                            :value="elevation.name"
                                            class="flex items-center justify-center gap-2 text-white bg-gray-800 aria-[current=true]:bg-grass9 rounded px-2 py-1">
                                                <TagsInputItemText class="text-sm" />
                                                <TagsInputItemDelete @click="removeElevation(elevation)">
                                                    <XMarkIcon class="h-4 w-4" />
                                                </TagsInputItemDelete>
                                        </TagsInputItem>

                                        <ComboboxInput
                                            v-model="elevationQuery"
                                            as-child
                                            @keydown.escape.stop="elevationQuery = ''">
                                            <TagsInputInput
                                                placeholder="Elevations..."
                                                class="border-none outline-none ring-0 focus:ring-0 focus:outline-none flex-1 rounded bg-transparent placeholder:text-gray-500 px-1"
                                                @keydown.enter.prevent/>
                                        </ComboboxInput>
                                </TagsInputRoot>

                                <ComboboxTrigger>
                                    <ChevronDownIcon class="h-4 w-4 text-gray-500" />
                                </ComboboxTrigger>
                            </ComboboxAnchor>

                        <ComboboxContent class="absolute z-10 w-full mt-2 bg-white overflow-hidden rounded shadow-[0px_10px_38px_-10px_rgba(22,_23,_24,_0.35),_0px_10px_20px_-15px_rgba(22,_23,_24,_0.2)] will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade">
                            <ComboboxViewport class="p-[5px]">
                                <ComboboxGroup v-if="filteredElevations.length">
                                    <ComboboxLabel class="px-[25px] text-xs leading-[25px] text-mauve11">
                                        Elevations
                                    </ComboboxLabel>

                                    <ComboboxItem
                                        v-for="(elevation, index) in filteredElevations"
                                        :key="index"
                                        class="text-[13px] leading-none text-grass11 rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-mauve8 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:bg-gray-800 data-[highlighted]:text-white"
                                        :value="elevation">
                                            <ComboboxItemIndicator
                                                class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                                                <CheckIcon class="h-4 w-4 text-white" />
                                            </ComboboxItemIndicator>
                                            <span>
                                                {{ elevation.name }}
                                            </span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="flavorNotes" value="Flavor Notes"/>
                    <ComboboxRoot
                        v-model="form.flavorNotes"
                        multiple
                        ignore-filter
                        class="w-full mx-auto relative border border-gray-300 rounded-md shadow-sm">
                            <ComboboxAnchor class="w-full inline-flex items-center justify-between p-2 text-[13px] rounded-md leading-none gap-[5px] bg-white  focus:shadow-black outline-none">
                                <TagsInputRoot
                                    v-model="form.flavorNotes"
                                    delimiter=""
                                    class="flex gap-2 items-center rounded-lg flex-wrap">
                                        <TagsInputItem
                                            v-for="flavorNote in form.flavor_notes"
                                            :key="flavorNote.id"
                                            :value="flavorNote.name"
                                            class="flex items-center justify-center gap-2 text-white bg-gray-800 aria-[current=true]:bg-grass9 rounded px-2 py-1">
                                                <TagsInputItemText class="text-sm" />
                                                <TagsInputItemDelete @click="removeFlavorNote(flavorNote)">
                                                    <XMarkIcon class="h-4 w-4" />
                                                </TagsInputItemDelete>
                                        </TagsInputItem>

                                        <ComboboxInput
                                            v-model="flavorNoteQuery"
                                            as-child
                                            @keydown.escape.stop="flavorNoteQuery = ''">
                                            <TagsInputInput
                                                placeholder="Flavor Notes..."
                                                class="border-none outline-none ring-0 focus:ring-0 focus:outline-none flex-1 rounded bg-transparent placeholder:text-gray-500 px-1"
                                                @keydown.enter.prevent/>
                                        </ComboboxInput>
                                </TagsInputRoot>

                                <ComboboxTrigger>
                                    <ChevronDownIcon class="h-4 w-4 text-gray-500" />
                                </ComboboxTrigger>
                            </ComboboxAnchor>

                        <ComboboxContent class="absolute z-10 w-full mt-2 bg-white overflow-hidden rounded shadow-[0px_10px_38px_-10px_rgba(22,_23,_24,_0.35),_0px_10px_20px_-15px_rgba(22,_23,_24,_0.2)] will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade">
                            <ComboboxViewport class="p-[5px]">
                                <ComboboxGroup v-if="filteredFlavorNotes.length">
                                    <ComboboxLabel class="px-[25px] text-xs leading-[25px] text-mauve11">
                                        Flavor Notes
                                    </ComboboxLabel>

                                    <ComboboxItem
                                        v-for="(flavorNote, index) in filteredFlavorNotes"
                                        :key="index"
                                        class="text-[13px] leading-none text-grass11 rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-mauve8 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:bg-gray-800 data-[highlighted]:text-white"
                                        :value="flavorNote">
                                            <ComboboxItemIndicator
                                                class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                                                <CheckIcon class="h-4 w-4 text-white" />
                                            </ComboboxItemIndicator>
                                            <span>
                                                {{ flavorNote.name }}
                                            </span>
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="w-full flex items-center justify-end gap-x-6 px-4 py-3">
                <DangerButton @click="deleteRoast()">Delete</DangerButton>
                <PrimaryButton @click="updateRoast()">Update</PrimaryButton>
            </div>
        </template>
    </Drawer>
</template>

<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Drawer from '@/Components/Drawer.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { ref, computed, watch } from 'vue';
import { useEventBus } from '@vueuse/core';
import { router, useForm, usePage } from '@inertiajs/vue3';

import { 
    ComboboxAnchor, 
    ComboboxContent, 
    ComboboxGroup, 
    ComboboxInput, 
    ComboboxItem, 
    ComboboxItemIndicator, 
    ComboboxLabel, 
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

const listener = ( event, data ) => {
    if ( event === 'prompt-edit-roast' ) {
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
    router.delete( route('platform.roasts.delete', { roast: form.id } ), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Roast deleted'
            });
        }
    } );
}
const updateRoast = () => {
    form.put( route('platform.roasts.update', { roast: form.id } ), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Roast updated'
            });

            close();
        }
    } );
}

const { contains } = useFilter({ sensitivity: 'base' });

const countries = computed(() => usePage().props.countries);

const countryQuery = ref('');

const filteredCountries = computed(() => countries.value.filter(country => contains(country.name, countryQuery.value)));

const removeCountry = (country) => {
    form.countries = form.countries.filter(c => c.id !== country.id);
}

const processes = computed(() => usePage().props.processes);

const processQuery = ref('');

const filteredProcesses = computed(() => processes.value.filter(process => contains(process.name, processQuery.value)));

const removeProcess = (process) => {
    form.processes = form.processes.filter(p => p.id !== process.id);
}

const varieties = computed(() => usePage().props.varieties);

const varietyQuery = ref('');

const filteredVarieties = computed(() => varieties.value.filter(variety => contains(variety.name, varietyQuery.value)));

const removeVariety = (variety) => {
    form.varieties = form.varieties.filter(v => v.id !== variety.id);
}

const elevations = computed(() => usePage().props.elevations);

const elevationQuery = ref('');

const filteredElevations = computed(() => elevations.value.filter(elevation => contains(elevation.name, elevationQuery.value)));

const removeElevation = (elevation) => {
    form.elevations = form.elevations.filter(e => e.id !== elevation.id);
}

const flavorNotes = computed(() => usePage().props.flavorNotes);

const flavorNoteQuery = ref('');

const filteredFlavorNotes = computed(() => flavorNotes.value.filter(flavorNote => contains(flavorNote.name, flavorNoteQuery.value)));

const removeFlavorNote = (flavorNote) => {
    form.flavorNotes = form.flavorNotes.filter(f => f.id !== flavorNote.id);
}
</script>