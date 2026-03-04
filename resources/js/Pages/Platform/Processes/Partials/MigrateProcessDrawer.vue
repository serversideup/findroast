<template>
    <Drawer :show="open" @close="close" max-width="xl" bg-color="bg-stone-50">
        <template #title>
            Migrate Process
        </template>

        <template #subtitle>
            Migrate "{{ process?.name }}" into another process
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-6">
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm p-6 space-y-5">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-sm text-amber-900">
                            <strong>What happens when you migrate:</strong>
                        </p>
                        <ul class="mt-2 text-sm text-amber-800 list-disc list-inside space-y-1">
                            <li>All roasts tagged with "{{ process?.name }}" will be re-tagged with the selected target process</li>
                            <li>Future imports of "{{ process?.name }}" will automatically use the target process</li>
                            <li>The original process will be archived for reference</li>
                        </ul>
                    </div>

                    <div>
                        <InputLabel for="target" value="Migrate To"/>
                        <select
                            id="target"
                            v-model="form.target_process_id"
                            class="mt-1.5 block w-full rounded-md border-stone-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                            <option :value="null">Select a target process...</option>
                            <option
                                v-for="target in targetProcesses"
                                :key="target.id"
                                :value="target.id">
                                {{ target.name }}
                            </option>
                        </select>
                        <p class="mt-1.5 text-xs text-stone-500">
                            Choose which process "{{ process?.name }}" should be migrated into
                        </p>
                        <InputError :message="form.errors.target_process_id" class="mt-2"/>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="w-full flex items-center justify-end gap-x-4 px-6 py-4 bg-white border-t border-stone-200">
                <SecondaryButton @click="close">Cancel</SecondaryButton>
                <PrimaryButton @click="migrateProcess" :disabled="!form.target_process_id">
                    Migrate Process
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
const process = ref(null);
const targetProcesses = ref([]);

const form = useForm({
    target_process_id: null
});

const notificationBus = useEventBus('roast-notification');

const migrateProcess = () => {
    form.post('/platform/processes/'+process.value.id+'/migrate', {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Process migrated successfully'
            });
            close();
        }
    });
}

const promptBus = useEventBus('roast-prompt-event-bus');
const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-migrate-process' ) {
        process.value = data.process;
        targetProcesses.value = data.targetProcesses;
        open.value = true;
    }
}
promptBus.on(promptBusListener);

const close = () => {
    open.value = false;
    form.reset();
    process.value = null;
    targetProcesses.value = [];
}
</script>
