<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-stone-900">Saved Filters & Notifications</h2>
            <p class="mt-1 text-sm text-stone-600">
                Manage your saved filter subscriptions. You'll receive daily email digests when new coffees match your saved filters.
            </p>
        </header>

        <div class="mt-6 space-y-4">
            <div v-if="subscriptions.length === 0" class="text-center py-8 bg-stone-50 rounded-lg border border-stone-200">
                <BellSlashIcon class="h-12 w-12 text-stone-400 mx-auto mb-3" />
                <p class="text-sm text-stone-600">You don't have any saved filter subscriptions yet.</p>
                <p class="text-xs text-stone-500 mt-1">
                    Visit the <a href="/" class="text-amber-700 hover:text-amber-800 underline">coffee search page</a> and click "Get notified" when you apply filters.
                </p>
            </div>

            <div
                v-for="subscription in subscriptions"
                :key="subscription.id"
                class="bg-white border border-stone-200 rounded-lg p-4 hover:border-stone-300 transition-colors"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <BellIcon class="h-4 w-4 text-amber-700 flex-shrink-0" />
                            <span class="text-sm font-medium text-stone-900">Daily Digest</span>
                        </div>

                        <div class="space-y-1.5 mb-2">
                            <div
                                v-if="subscription.search"
                                class="text-xs text-stone-600"
                            >
                                <span class="font-medium">Search:</span> "{{ subscription.search }}"
                            </div>
                            <div
                                v-for="(names, filterType) in getFilterNames(subscription)"
                                :key="filterType"
                                class="text-xs text-stone-600"
                            >
                                <span class="font-medium">{{ getFilterLabel(filterType) }}:</span> {{ names.join(', ') }}
                            </div>
                        </div>

                        <p class="text-xs text-stone-500">
                            Created {{ formatDate(subscription.created_at) }}
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="deleteSubscription(subscription.id)"
                        :disabled="deletingId === subscription.id"
                        class="text-stone-400 hover:text-red-600 p-1 rounded transition-colors disabled:opacity-50"
                        :class="{ 'opacity-50 cursor-not-allowed': deletingId === subscription.id }"
                    >
                        <TrashIcon class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { BellIcon, BellSlashIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    subscriptions: {
        type: Array,
        default: () => []
    }
});

const deletingId = ref(null);

const filterLabels = {
    countries: 'Origins',
    processes: 'Processes',
    flavor_notes: 'Flavors',
    varieties: 'Varieties',
    companies: 'Roasters'
};

const getFilterLabel = (filterType) => {
    return filterLabels[filterType] || filterType;
};

const getFilterNames = (subscription) => {
    const names = {};
    if (subscription.resolved_filters) {
        for (const [key, value] of Object.entries(subscription.resolved_filters)) {
            if (Array.isArray(value) && value.length > 0) {
                names[key] = value;
            }
        }
    }
    return names;
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 0) {
        return 'today';
    } else if (diffDays === 1) {
        return 'yesterday';
    } else if (diffDays < 7) {
        return `${diffDays} days ago`;
    } else if (diffDays < 30) {
        const weeks = Math.floor(diffDays / 7);
        return `${weeks} ${weeks === 1 ? 'week' : 'weeks'} ago`;
    } else {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }
};

const deleteSubscription = (id) => {
    if (!confirm('Are you sure you want to delete this subscription? You will no longer receive notifications for these filters.')) {
        return;
    }

    deletingId.value = id;

    useForm({}).delete(route('subscriptions.destroy', id), {
        preserveScroll: true,
        onFinish: () => {
            deletingId.value = null;
        }
    });
};
</script>
