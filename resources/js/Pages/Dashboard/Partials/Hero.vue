<template>
    <div class="relative bg-white">
        <div
            class="mx-auto max-w-7xl lg:grid lg:grid-cols-12 lg:gap-x-8 lg:px-8">
            <div
                class="px-6 pb-24 pt-10 sm:pb-32 lg:col-span-7 lg:px-0 lg:pb-56 lg:pt-48 xl:col-span-6">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <h1
                        class="mt-24 text-4xl font-bold tracking-tight text-gray-900 sm:mt-10 sm:text-6xl">
                        Discover your next favorite cup of coffee
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Roast is a platform built for coffee enthusiasts to discover offerings from the best coffee companies in the world. 
                        Find your next favorite cup of coffee today!
                    </p>
                    <div class="mt-10 flex items-center gap-x-4">
                        <PrimaryLink
                            href="/directory">
                            Browse Current Offerings
                        </PrimaryLink>
                        <SecondaryLink
                            href="/submissions">
                            Submit a Company
                        </SecondaryLink>
                    </div>
                </div>
            </div>
            <div
                class="relative lg:col-span-5 lg:-mr-8 xl:absolute xl:inset-0 xl:left-1/2 xl:mr-0">
                <img
                    class="aspect-[3/2] w-full bg-gray-50 object-cover lg:absolute lg:inset-0 lg:aspect-auto lg:h-full"
                    src="/stock/coffee-roaster.jpg"
                    alt="" />
            </div>
        </div>
    </div>
</template>

<script setup>
import PrimaryLink from '@/Components/PrimaryLink.vue';
import SecondaryLink from '@/Components/SecondaryLink.vue';
</script>