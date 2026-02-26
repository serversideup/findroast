import { router } from '@inertiajs/vue3'

/**
 * Plausible Analytics Plugin for Inertia.js
 *
 * This plugin integrates Plausible Analytics with Inertia.js to track
 * page views on client-side navigation. It hooks into Inertia's navigation
 * events to send pageview events to Plausible.
 */
export default {
    install: (app) => {
        // Only track if Plausible is loaded
        if (typeof window.plausible === 'undefined') {
            return
        }

        // Track initial page load
        window.plausible('pageview')

        // Track Inertia page visits
        router.on('navigate', (event) => {
            // Use setTimeout to ensure the URL has been updated
            setTimeout(() => {
                window.plausible('pageview')
            }, 0)
        })
    }
}
