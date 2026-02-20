<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { PhotoIcon, TrashIcon } from '@heroicons/vue/24/outline';

const page = usePage();
const user = computed(() => page.props.auth.user);

const profilePictureInput = ref(null);
const profilePicturePreview = ref(null);

const form = useForm({
    profile_picture: null,
});

const removeForm = useForm({});

const profilePictureState = computed(() => {
    if (profilePicturePreview.value) {
        return 'selected-profile-picture';
    } else if (user.value.avatar && !user.value.avatar.includes('gravatar')) {
        return 'user-profile-picture';
    }
    return 'no-profile-picture';
});

const selectNewProfilePicture = () => {
    profilePictureInput.value.click();
};

const handleProfilePictureChange = (e) => {
    const file = e.target.files[0];

    if (!file) return;

    form.profile_picture = file;

    const reader = new FileReader();
    reader.onload = (e) => {
        profilePicturePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const submit = () => {
    form.post(route('profile.picture.update'), {
        preserveScroll: true,
        onSuccess: () => {
            profilePicturePreview.value = null;
            form.reset();
            if (profilePictureInput.value) {
                profilePictureInput.value.value = null;
            }
        },
    });
};

const removeProfilePicture = () => {
    removeForm.delete(route('profile.picture.remove'), {
        preserveScroll: true,
        onSuccess: () => {
            profilePicturePreview.value = null;
        },
    });
};
</script>

<template>
    <section>
        <header class="border-b border-stone-200 pb-4">
            <h2 class="text-lg font-semibold text-stone-900">Profile Picture</h2>
            <p class="mt-1 text-sm text-stone-600">Update your profile picture.</p>
        </header>

        <div class="mt-6">
            <!-- Current/Preview Profile Picture -->
            <div class="flex items-center gap-6">
                <div class="relative">
                    <img
                        v-if="profilePictureState === 'selected-profile-picture'"
                        :src="profilePicturePreview"
                        alt="Profile preview"
                        class="h-24 w-24 rounded-full object-cover border-2 border-stone-200"
                    />
                    <img
                        v-else-if="profilePictureState === 'user-profile-picture'"
                        :src="user.avatar"
                        alt="Profile picture"
                        class="h-24 w-24 rounded-full object-cover border-2 border-stone-200"
                    />
                    <div
                        v-else
                        class="h-24 w-24 rounded-full bg-stone-100 border-2 border-stone-200 flex items-center justify-center"
                    >
                        <PhotoIcon class="h-12 w-12 text-stone-400" />
                    </div>
                </div>

                <div class="flex-1">
                    <!-- Upload Section -->
                    <form @submit.prevent="submit" v-if="profilePictureState === 'selected-profile-picture'">
                        <div class="flex items-center gap-3">
                            <PrimaryButton
                                type="submit"
                                :disabled="form.processing"
                                variant="amber"
                            >
                                {{ form.processing ? 'Uploading...' : 'Upload Picture' }}
                            </PrimaryButton>
                            <button
                                type="button"
                                @click="profilePicturePreview = null; form.reset()"
                                class="text-sm text-stone-600 hover:text-stone-900"
                            >
                                Cancel
                            </button>
                        </div>
                        <InputError :message="form.errors.profile_picture" class="mt-2" />
                    </form>

                    <div v-else>
                        <input
                            ref="profilePictureInput"
                            type="file"
                            class="hidden"
                            accept="image/*"
                            @change="handleProfilePictureChange"
                        />

                        <div class="flex items-center gap-3">
                            <PrimaryButton
                                type="button"
                                @click="selectNewProfilePicture"
                                variant="amber"
                            >
                                Select Picture
                            </PrimaryButton>

                            <button
                                v-if="profilePictureState === 'user-profile-picture'"
                                type="button"
                                @click="removeProfilePicture"
                                :disabled="removeForm.processing"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-stone-300 rounded-lg text-sm font-medium text-stone-700 hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 disabled:opacity-50"
                            >
                                <TrashIcon class="h-4 w-4" />
                                {{ removeForm.processing ? 'Removing...' : 'Remove' }}
                            </button>
                        </div>

                        <p class="mt-2 text-xs text-stone-600">
                            JPG, PNG, or GIF. Max file size 2MB.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Success Messages -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-1"
            >
                <p
                    v-if="page.props.status === 'profile-picture-updated'"
                    class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2"
                >
                    Profile picture updated successfully.
                </p>
                <p
                    v-else-if="page.props.status === 'profile-picture-removed'"
                    class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2"
                >
                    Profile picture removed successfully.
                </p>
            </Transition>
        </div>
    </section>
</template>
