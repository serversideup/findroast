<template>
    <Drawer :show="open" @close="close" max-width="xl" bg-color="bg-stone-50">
        <template #title>
            Migrate Variety
        </template>

        <template #subtitle>
            Migrate "{{ variety?.name }}" into another variety
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-6">
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm p-6 space-y-5">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-sm text-amber-900">
                            <strong>What happens when you migrate:</strong>
                        </p>
                        <ul class="mt-2 text-sm text-amber-800 list-disc list-inside space-y-1">
                            <li>All roasts tagged with "{{ variety?.name }}" will be re-tagged with the selected target variety</li>
                            <li>Future imports of "{{ variety?.name }}" will automatically use the target variety</li>
                            <li>The original variety will be archived for reference</li>
                        </ul>
                    </div>

                    <div>
                        <InputLabel for="target" value="Migrate To"/>
                        <select
                            id="target"
                            v-model="form.target_variety_id"
                            class="mt-1.5 block w-full rounded-md border-stone-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                            <option :value="null">Select a target variety...</option>
                            <option
                                v-for="target in targetVarieties"
                                :key="target.id"
                                :value="target.id">
                                {{ target.name }}
                            </option>
                        </select>
                        <p class="mt-1.5 text-xs text-stone-500">
                            Choose which variety "{{ variety?.name }}" should be migrated into
                        </p>
                        <InputError :message="form.errors.target_variety_id" class="mt-2"/>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="w-full flex items-center justify-end gap-x-4 px-6 py-4 bg-white border-t border-stone-200">
                <SecondaryButton @click="close">Cancel</SecondaryButton>
                <PrimaryButton @click="migrateVariety" :disabled="!form.target_variety_id">
                    Migrate Variety
                </PrimaryButton>
            </div>
        </template>
    </Drawer>
</template>

<script setup>
import Drawer from '@/Components/Drawer.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';
import { useEventBus } from '@vueuse/core';
import { useForm } from '@inertiajs/vue3';

const open = ref(false);
const variety = ref(null);
const targetVarieties = ref([]);

const form = useForm({
    target_variety_id: null
});

const notificationBus = useEventBus('roast-notification');

const migrateVariety = () => {
    form.post('/platform/varieties/'+variety.value.id+'/migrate', {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Variety migrated successfully'
            });
            close();
        }
    });
}

const promptBus = useEventBus('roast-prompt-event-bus');
const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-migrate-variety' ) {
        variety.value = data.variety;
        targetVarieties.value = data.targetVarieties;
        open.value = true;
    }
}
promptBus.on(promptBusListener);

const close = () => {
    open.value = false;
    form.reset();
    variety.value = null;
    targetVarieties.value = [];
}
</script>
