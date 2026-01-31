<template>
    <Head :title="'Edit: ' + recipe.name" />
    
    <div class="bg-gray-50 min-h-screen">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <Link :href="route('recipes.show', recipe.slug)" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Recipe
                        </Link>
                        <h1 class="mt-2 text-2xl font-bold text-gray-900">Edit Recipe</h1>
                    </div>
                    <DangerButton @click="confirmDelete = true">
                        Delete
                    </DangerButton>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="space-y-8">
                <!-- Basic Info Section -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Recipe Name -->
                        <div class="sm:col-span-2">
                            <InputLabel for="name" value="Recipe Name" />
                            <TextInput 
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., My Perfect V60"
                                required
                            />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <!-- Brew Method -->
                        <div>
                            <InputLabel for="brew_method_id" value="Brew Method" />
                            <select 
                                id="brew_method_id"
                                v-model="form.brew_method_id"
                                class="mt-1 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm"
                                required
                            >
                                <option :value="null" disabled>Select a brew method</option>
                                <option v-for="method in brewMethods" :key="method.id" :value="method.id">
                                    {{ method.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.brew_method_id" class="mt-2" />
                        </div>

                        <!-- Visibility -->
                        <div>
                            <InputLabel for="is_public" value="Visibility" />
                            <select 
                                id="is_public"
                                v-model="form.is_public"
                                class="mt-1 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm"
                            >
                                <option :value="true">Public</option>
                                <option :value="false">Private</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-2">
                            <InputLabel for="description" value="Description (optional)" />
                            <TextAreaInput 
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full"
                                rows="3"
                                placeholder="Describe your recipe..."
                            />
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Recipe Parameters Section -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Recipe Parameters</h2>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <!-- Coffee Dose -->
                        <div>
                            <InputLabel for="coffee_dose" value="Coffee Dose" />
                            <TextInput 
                                id="coffee_dose"
                                v-model="form.coffee_dose"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., 18g"
                            />
                            <InputError :message="form.errors.coffee_dose" class="mt-2" />
                        </div>

                        <!-- Water Amount -->
                        <div>
                            <InputLabel for="water_amount" value="Water Amount" />
                            <TextInput 
                                id="water_amount"
                                v-model="form.water_amount"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., 300ml"
                            />
                            <InputError :message="form.errors.water_amount" class="mt-2" />
                        </div>

                        <!-- Water Temperature -->
                        <div>
                            <InputLabel for="water_temperature" value="Water Temperature" />
                            <TextInput 
                                id="water_temperature"
                                v-model="form.water_temperature"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., 93°C / 200°F"
                            />
                            <InputError :message="form.errors.water_temperature" class="mt-2" />
                        </div>

                        <!-- Grind Size -->
                        <div>
                            <InputLabel for="grind_size" value="Grind Size" />
                            <TextInput 
                                id="grind_size"
                                v-model="form.grind_size"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., Medium-fine (20 clicks)"
                            />
                            <InputError :message="form.errors.grind_size" class="mt-2" />
                        </div>

                        <!-- Total Brew Time -->
                        <div>
                            <InputLabel for="total_brew_time" value="Total Brew Time" />
                            <TextInput 
                                id="total_brew_time"
                                v-model="form.total_brew_time"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., 3:00 - 3:30"
                            />
                            <InputError :message="form.errors.total_brew_time" class="mt-2" />
                        </div>

                        <!-- Yield -->
                        <div>
                            <InputLabel for="yield" value="Yield" />
                            <TextInput 
                                id="yield"
                                v-model="form.yield"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., ~250ml"
                            />
                            <InputError :message="form.errors.yield" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Steps Section -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Brewing Steps</h2>
                        <SecondaryButton type="button" @click="addStep">
                            + Add Step
                        </SecondaryButton>
                    </div>

                    <InputError :message="form.errors.steps" class="mb-4" />

                    <div class="space-y-4">
                        <div 
                            v-for="(step, index) in form.steps" 
                            :key="index"
                            class="border border-gray-200 rounded-lg p-4"
                        >
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="flex items-center justify-center w-8 h-8 bg-gray-800 rounded-full text-white text-sm font-semibold">
                                        {{ index + 1 }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-500">Step {{ index + 1 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button 
                                        v-if="index > 0"
                                        type="button"
                                        @click="moveStep(index, -1)"
                                        class="p-1 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <button 
                                        v-if="index < form.steps.length - 1"
                                        type="button"
                                        @click="moveStep(index, 1)"
                                        class="p-1 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <button 
                                        v-if="form.steps.length > 1"
                                        type="button"
                                        @click="removeStep(index)"
                                        class="p-1 text-red-400 hover:text-red-600"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- Step Title -->
                                <div class="sm:col-span-2">
                                    <InputLabel :for="'step_title_' + index" value="Title" />
                                    <TextInput 
                                        :id="'step_title_' + index"
                                        v-model="step.title"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="e.g., Bloom"
                                        required
                                    />
                                    <InputError :message="form.errors['steps.' + index + '.title']" class="mt-2" />
                                </div>

                                <!-- Duration -->
                                <div>
                                    <InputLabel :for="'step_duration_' + index" value="Duration (optional)" />
                                    <TextInput 
                                        :id="'step_duration_' + index"
                                        v-model="step.duration"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="e.g., 0:00 - 0:45"
                                    />
                                </div>

                                <!-- Water Amount -->
                                <div>
                                    <InputLabel :for="'step_water_' + index" value="Water Amount (optional)" />
                                    <TextInput 
                                        :id="'step_water_' + index"
                                        v-model="step.water_amount"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="e.g., 50ml"
                                    />
                                </div>

                                <!-- Description -->
                                <div class="sm:col-span-2">
                                    <InputLabel :for="'step_description_' + index" value="Description (optional)" />
                                    <TextAreaInput 
                                        :id="'step_description_' + index"
                                        v-model="step.description"
                                        class="mt-1 block w-full"
                                        rows="2"
                                        placeholder="Describe this step..."
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <SecondaryButton type="button" @click="addStep">
                            + Add Another Step
                        </SecondaryButton>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-4">
                    <Link :href="route('recipes.show', recipe.slug)" class="text-sm text-gray-600 hover:text-gray-900">
                        Cancel
                    </Link>
                    <PrimaryButton type="submit" :disabled="form.processing">
                        <span v-if="form.processing">Saving...</span>
                        <span v-else>Save Changes</span>
                    </PrimaryButton>
                </div>
            </div>
        </form>

        <!-- Delete Confirmation Modal -->
        <Modal :show="confirmDelete" @close="confirmDelete = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this recipe?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    This action cannot be undone. The recipe and all its steps will be permanently deleted.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmDelete = false">
                        Cancel
                    </SecondaryButton>
                    <DangerButton @click="deleteRecipe" :disabled="deleteForm.processing">
                        Delete Recipe
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const recipe = computed(() => usePage().props.recipe);
const brewMethods = computed(() => usePage().props.brewMethods);

const confirmDelete = ref(false);

const form = useForm({
    brew_method_id: recipe.value.brew_method_id,
    name: recipe.value.name,
    description: recipe.value.description || '',
    coffee_dose: recipe.value.coffee_dose || '',
    water_amount: recipe.value.water_amount || '',
    water_temperature: recipe.value.water_temperature || '',
    grind_size: recipe.value.grind_size || '',
    total_brew_time: recipe.value.total_brew_time || '',
    yield: recipe.value.yield || '',
    is_public: recipe.value.is_public,
    steps: recipe.value.steps.map(step => ({
        id: step.id,
        title: step.title,
        description: step.description || '',
        duration: step.duration || '',
        water_amount: step.water_amount || '',
    })),
});

const deleteForm = useForm({});

const addStep = () => {
    form.steps.push({ title: '', description: '', duration: '', water_amount: '' });
};

const removeStep = (index) => {
    form.steps.splice(index, 1);
};

const moveStep = (index, direction) => {
    const newIndex = index + direction;
    if (newIndex >= 0 && newIndex < form.steps.length) {
        const step = form.steps.splice(index, 1)[0];
        form.steps.splice(newIndex, 0, step);
    }
};

const submit = () => {
    form.put(route('recipes.update', recipe.value.slug));
};

const deleteRecipe = () => {
    deleteForm.delete(route('recipes.destroy', recipe.value.slug));
};
</script>



