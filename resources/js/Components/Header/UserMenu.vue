<template>
    <Menu as="div">
        <MenuButton>
            <img :src="user.avatar" :alt="user.name" class="h-10 w-10 rounded-full" />
        </MenuButton>
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0">
            <MenuItems
                class="absolute right-0 mt-2 w-60 origin-top-right divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-black/5 z-50 focus:outline-none">
                <div class="py-3 px-4 flex flex-col">
                    <span class="font-semibold font-sans text-[#344054] text-sm">{{ user.name }}</span>
                    <span class="font-sans text-[#475467] text-sm">{{ user.email }}</span>
                </div>
                <div class="py-1 px-[6px]">
                    <MenuItem v-slot="{ active }">
                        <Link href="/profile" 
                            class="flex items-center p-[10px] font-sans text-[#475467] text-sm rounded-md" 
                            :class="{ 
                                'bg-[#F9FAFB]': active 
                            }">
                                <UserIcon class="w-4 h-4 mr-2"/>
                                <span class="text-[#344054] font-sans font-medium text-sm">My Account</span>
                        </Link>
                    </MenuItem>
                </div>
                <!-- <div class="py-1 space-y-[2px] px-[6px]">
                    // Brew Log
                    // Likes
                </div> -->
                <div class="py-1 space-y-[2px] px-[6px]" v-if="user.permission == 'admin'">
                    <MenuItem v-slot="{ active }">
                        <Link href="/platform" 
                            class="flex items-center p-[10px] font-sans text-[#475467] text-sm rounded-md" 
                            :class="{ 
                                'bg-[#F9FAFB]': active 
                            }">
                                <Cog6ToothIcon class="w-4 h-4 mr-2"/>
                                <span class="text-[#344054] font-sans font-medium text-sm">Platform Settings</span>
                        </Link>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    Menu,
    MenuButton,
    MenuItems,
    MenuItem
} from '@headlessui/vue';
import {
    UserIcon,
    Cog6ToothIcon
} from '@heroicons/vue/24/outline';

const user = computed(() => usePage().props.auth.user);

// const items = [
//     [{
//         label: user.value.email,
//         slot: 'account',
//         disabled: true
//     }],
//     [{
//         label: 'My Account',
//         icon: 'i-heroicons-user',
//         to: '/account'
//     },{
//         label: 'Submissions',
//         icon: 'i-heroicons-squares-plus',
//         to: '/account/submissions'
//     }],
//     [{
//         label: 'System Settings',
//         icon: 'i-heroicons-cog',
//         to: '/system-settings'
//     }],
//     // [{
//     //     label: 'Brew Log',
//     //     icon: 'i-heroicons-pencil-square',
//     //     to: '/brew-log'
//     // }],
//     // [{
//     //     label: 'Recipes',
//     //     icon: 'i-heroicons-book-open'
//     // }],
//     [{
//         label: 'Sign out',
//         icon: 'i-heroicons-logout'
//     }]
// ]
</script>