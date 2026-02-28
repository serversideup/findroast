<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-stone-900">API Access Tokens</h2>
            <p class="mt-1 text-sm text-stone-600">
                Create personal access tokens to query roast data from external applications.
                <Link href="/api-docs" class="text-amber-700 hover:text-amber-800 underline">View API documentation</Link>.
            </p>
        </header>

        <!-- Create Token Form -->
        <form @submit.prevent="createToken" class="mt-6 flex gap-3">
            <input
                v-model="form.name"
                type="text"
                placeholder="Token name (e.g. My App)"
                class="flex-1 rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                maxlength="255"
                required
            />
            <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex items-center gap-1.5 rounded-md bg-amber-700 px-4 py-2 text-sm font-medium text-white hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <KeyIcon class="h-4 w-4" />
                Create Token
            </button>
        </form>
        <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>

        <!-- Token List -->
        <div class="mt-6 space-y-3">
            <div v-if="tokens.length === 0" class="text-center py-8 bg-stone-50 rounded-lg border border-stone-200">
                <KeyIcon class="h-12 w-12 text-stone-400 mx-auto mb-3" />
                <p class="text-sm text-stone-600">No API tokens yet.</p>
                <p class="text-xs text-stone-500 mt-1">Create a token above to start using the FindRoast API.</p>
            </div>

            <div
                v-for="token in tokens"
                :key="token.id"
                class="flex items-center justify-between gap-4 bg-white border border-stone-200 rounded-lg px-4 py-3 hover:border-stone-300 transition-colors"
            >
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-stone-900 truncate">{{ token.name }}</p>
                    <p class="text-xs text-stone-500 mt-0.5">
                        Created {{ formatDate(token.created_at) }}
                        &middot;
                        {{ token.last_used_at ? 'Last used ' + formatDate(token.last_used_at) : 'Never used' }}
                    </p>
                </div>
                <button
                    type="button"
                    @click="revokeToken(token.id)"
                    :disabled="revokingId === token.id"
                    class="text-stone-400 hover:text-red-600 p-1 rounded transition-colors disabled:opacity-50"
                    title="Revoke token"
                >
                    <TrashIcon class="h-4 w-4" />
                </button>
            </div>
        </div>

        <!-- New Token Modal -->
        <TransitionRoot appear :show="showNewTokenModal" as="template">
            <Dialog as="div" class="relative z-50" @close="dismissModal">
                <TransitionChild
                    as="template"
                    enter="ease-out duration-200"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in duration-150"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/40" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <TransitionChild
                            as="template"
                            enter="ease-out duration-200"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="ease-in duration-150"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                                <DialogTitle class="text-base font-semibold text-stone-900 flex items-center gap-2">
                                    <KeyIcon class="h-5 w-5 text-amber-700" />
                                    Your New API Token
                                </DialogTitle>

                                <p class="mt-3 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-md px-3 py-2">
                                    Copy this token now. It will not be shown again.
                                </p>

                                <div class="mt-4 flex items-center gap-2">
                                    <code class="flex-1 rounded-md bg-stone-100 border border-stone-200 px-3 py-2 text-xs font-mono text-stone-800 break-all select-all">{{ localNewToken }}</code>
                                    <button
                                        type="button"
                                        @click="copyToken"
                                        class="flex-shrink-0 p-2 rounded-md border border-stone-200 hover:bg-stone-100 text-stone-600 hover:text-stone-900 transition-colors"
                                        :title="copied ? 'Copied!' : 'Copy to clipboard'"
                                    >
                                        <ClipboardDocumentCheckIcon v-if="copied" class="h-4 w-4 text-green-600" />
                                        <ClipboardDocumentIcon v-else class="h-4 w-4" />
                                    </button>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button
                                        type="button"
                                        @click="dismissModal"
                                        class="rounded-md bg-amber-700 px-4 py-2 text-sm font-medium text-white hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                                    >
                                        I've copied it, close
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </section>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { KeyIcon, TrashIcon, ClipboardDocumentIcon, ClipboardDocumentCheckIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    tokens: {
        type: Array,
        default: () => []
    },
    newToken: {
        type: String,
        default: null
    }
});

const form = useForm({ name: '' });
const revokingId = ref(null);
const localNewToken = ref(props.newToken);
const copied = ref(false);

const showNewTokenModal = computed(() => !!localNewToken.value);

watch(() => props.newToken, (val) => {
    localNewToken.value = val;
    copied.value = false;
});

const createToken = () => {
    form.post(route('tokens.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        }
    });
};

const revokeToken = (id) => {
    if (!confirm('Revoke this token? Any apps using it will lose access immediately.')) return;

    revokingId.value = id;

    router.delete(route('tokens.destroy', id), {
        preserveScroll: true,
        onFinish: () => {
            revokingId.value = null;
        }
    });
};

const copyToken = async () => {
    if (!localNewToken.value) return;
    await navigator.clipboard.writeText(localNewToken.value);
    copied.value = true;
    setTimeout(() => { copied.value = false; }, 2000);
};

const dismissModal = () => {
    localNewToken.value = null;
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));

    if (diffDays === 0) return 'today';
    if (diffDays === 1) return 'yesterday';
    if (diffDays < 7) return `${diffDays} days ago`;
    if (diffDays < 30) {
        const weeks = Math.floor(diffDays / 7);
        return `${weeks} ${weeks === 1 ? 'week' : 'weeks'} ago`;
    }
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>
