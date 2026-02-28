<template>
    <footer class="bg-white border-t border-stone-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand Column -->
                <div class="md:col-span-1">
                    <Link href="/" class="inline-block mb-4">
                        <img src="/images/logos/header-logo.svg" class="h-6" alt="FindRoast" />
                    </Link>
                    <p class="text-stone-600 text-sm leading-relaxed">
                        Discover exceptional specialty coffee from roasters around the world.
                    </p>
                </div>

                <!-- Discover Column -->
                <div>
                    <h3 class="text-stone-900 font-semibold mb-4">Discover</h3>
                    <ul class="space-y-3">
                        <li>
                            <Link
                                href="/"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                Coffee Offerings
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/companies"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                Roasters
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/map"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                Cafe Map
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Company Column -->
                <div>
                    <h3 class="text-stone-900 font-semibold mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li>
                            <Link
                                href="/transparency"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                Transparency
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/changelog"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                Changelog
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/api-docs"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                API Docs
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/contact"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                Contact
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Connect Column -->
                <div>
                    <h3 class="text-stone-900 font-semibold mb-4">Connect</h3>
                    <ul class="space-y-3">
                        <li>
                            <a
                                href="https://github.com/serversideup/findroast"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                </svg>
                                GitHub
                            </a>
                        </li>
                        <li v-if="user">
                            <Link
                                href="/profile"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                Your Profile
                            </Link>
                        </li>
                        <li v-if="user && user.is_admin">
                            <Link
                                href="/platform"
                                class="text-stone-600 hover:text-amber-700 transition-colors text-sm">
                                Admin
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="mt-12 pt-8 border-t border-stone-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex flex-col items-center sm:items-start gap-2">
                        <p class="text-stone-500 text-sm text-center sm:text-left">
                            &copy; {{ currentYear }} FindRoast. Built with love for the coffee community.
                        </p>
                        <div class="flex items-center gap-4">
                            <Link
                                href="/privacy"
                                class="text-stone-500 hover:text-stone-700 transition-colors text-xs">
                                Privacy Policy
                            </Link>
                            <span class="text-stone-300">•</span>
                            <Link
                                href="/terms"
                                class="text-stone-500 hover:text-stone-700 transition-colors text-xs">
                                Terms of Service
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <a
                            href="https://github.com/serversideup/findroast"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-stone-400 hover:text-stone-600 transition-colors">
                            <span class="sr-only">GitHub</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const currentYear = new Date().getFullYear();
</script>
