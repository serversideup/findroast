<template>
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-gray-600">
                    Enhance your coffee experience
                </h2>
                <p
                    class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Tools for the modern coffee enthusiast
                </p>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Take your coffee to the next level with ROAST's suite of tools and features. 
                    Use our platform to track brews, experiment with different recipes, 
                    contribute your favorite companies, and more.
                </p>
            </div>
            <div
                class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl
                    class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div
                        v-for="feature in features"
                        :key="feature.name"
                        class="flex flex-col">
                        <dt
                            class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <component
                                :is="feature.icon"
                                class="h-5 w-5 flex-none text-gray-600"
                                aria-hidden="true" />
                            {{ feature.name }}
                        </dt>
                        <dd
                            class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">{{ feature.description }}</p>
                            <p class="mt-6">
                                <a
                                    :href="feature.href"
                                    class="text-sm font-semibold leading-6 text-gray-600">
                                    Learn more
                                    <span aria-hidden="true">→</span>
                                </a>
                            </p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    ArrowPathIcon,
    CloudArrowUpIcon,
    LockClosedIcon,
} from "@heroicons/vue/20/solid";

const features = [
    {
        name: "Push to deploy",
        description:
            "Commodo nec sagittis tortor mauris sed. Turpis tortor quis scelerisque diam id accumsan nullam tempus. Pulvinar etiam lacus volutpat eu. Phasellus praesent ligula sit faucibus.",
        href: "#",
        icon: CloudArrowUpIcon,
    },
    {
        name: "SSL certificates",
        description:
            "Pellentesque enim a commodo malesuada turpis eleifend risus. Facilisis donec placerat sapien consequat tempor fermentum nibh.",
        href: "#",
        icon: LockClosedIcon,
    },
    {
        name: "Simple queues",
        description:
            "Pellentesque sit elit congue ante nec amet. Dolor aenean curabitur viverra suspendisse iaculis eget. Nec mollis placerat ultricies euismod ut condimentum.",
        href: "#",
        icon: ArrowPathIcon,
    },
];
</script>
