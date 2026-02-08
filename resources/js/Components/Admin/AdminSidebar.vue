<template>
    <div
        :class="[
            'bg-stone-50 border-r border-stone-200 h-screen overflow-y-auto transition-all duration-300 flex flex-col',
            isCollapsed ? 'w-16' : 'w-56'
        ]"
    >
        <div class="flex-1 py-4 space-y-1">
            <!-- Company Management -->
            <NavCategory
                :icon="BuildingOffice2Icon"
                title="Companies"
                :collapsed="isCollapsed"
                :items="companyItems"
            />

            <!-- Cafe Management -->
            <NavCategory
                :icon="BuildingStorefrontIcon"
                title="Cafes"
                :collapsed="isCollapsed"
                :items="cafeItems"
            />

            <!-- Roast Management -->
            <NavCategory
                :icon="FireIcon"
                title="Roasts"
                :collapsed="isCollapsed"
                :items="roastItems"
            />

            <!-- Platform Management -->
            <NavCategory
                :icon="Cog6ToothIcon"
                title="Platform"
                :collapsed="isCollapsed"
                :items="platformItems"
            />
        </div>
    </div>
</template>

<script setup>
import {
    BuildingOffice2Icon,
    BuildingStorefrontIcon,
    FireIcon,
    Cog6ToothIcon,
} from '@heroicons/vue/24/outline';
import {
    BuildingOfficeIcon,
    SparklesIcon,
    BeakerIcon,
    CubeIcon,
    TagIcon,
    GlobeAmericasIcon,
    ArrowTrendingUpIcon,
    RectangleStackIcon,
    UsersIcon,
    ChatBubbleLeftIcon,
    ClipboardDocumentListIcon,
} from '@heroicons/vue/20/solid';
import NavCategory from './NavCategory.vue';

defineProps({
    isCollapsed: {
        type: Boolean,
        default: false
    }
});

const companyItems = [
    { label: 'Companies', href: '/platform/companies', icon: BuildingOfficeIcon }
];

const cafeItems = [
    { label: 'Cafes', href: '/platform/cafes', icon: BuildingStorefrontIcon },
    { label: 'Amenities', href: '/platform/amenities', icon: SparklesIcon },
    { label: 'Brew Methods', href: '/platform/brew-methods', icon: BeakerIcon },
    { label: 'Drink Options', href: '/platform/drink-options', icon: CubeIcon },
];

const roastItems = [
    { label: 'Roasts', href: '/platform/roasts', icon: FireIcon },
    { label: 'Flavor Notes', href: '/platform/flavor-notes', icon: TagIcon },
    { label: 'Countries', href: '/platform/countries', icon: GlobeAmericasIcon },
    { label: 'Elevations', href: '/platform/elevations', icon: ArrowTrendingUpIcon },
    { label: 'Processes', href: '/platform/processes', icon: ClipboardDocumentListIcon },
    { label: 'Varieties', href: '/platform/varieties', icon: RectangleStackIcon },
];

const platformItems = [
    { label: 'Users', href: '/platform/users', icon: UsersIcon },
    { label: 'Messages', href: '/platform/messages', icon: ChatBubbleLeftIcon },
];
</script>
