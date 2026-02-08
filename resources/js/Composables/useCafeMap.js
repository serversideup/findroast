import { reactive, ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const filters = reactive({
    search: '',
    brewMethods: [],
    amenities: [],
    drinkOptions: [],
});

const selectedCafe = ref(null);
const hoveredCafe = ref(null);

export const useCafeMap = () => {
    const allCafes = computed(() => usePage().props.cafes || []);

    const filteredCafes = computed(() => {
        return allCafes.value.filter(cafe => {
            // Text search
            if (filters.search) {
                const q = filters.search.toLowerCase();
                const match =
                    cafe.name?.toLowerCase().includes(q) ||
                    cafe.city?.toLowerCase().includes(q) ||
                    cafe.state?.toLowerCase().includes(q) ||
                    cafe.company?.name?.toLowerCase().includes(q);
                if (!match) return false;
            }

            // Brew methods filter
            if (filters.brewMethods.length > 0) {
                const cafeMethodIds = (cafe.brew_methods || []).map(m => m.id);
                if (!filters.brewMethods.some(id => cafeMethodIds.includes(id))) return false;
            }

            // Amenities filter
            if (filters.amenities.length > 0) {
                const cafeAmenityIds = (cafe.amenities || []).map(a => a.id);
                if (!filters.amenities.some(id => cafeAmenityIds.includes(id))) return false;
            }

            // Drink options filter
            if (filters.drinkOptions.length > 0) {
                const cafeDrinkIds = (cafe.drink_options || []).map(d => d.id);
                if (!filters.drinkOptions.some(id => cafeDrinkIds.includes(id))) return false;
            }

            return true;
        });
    });

    const totalActiveFilters = computed(() =>
        filters.brewMethods.length + filters.amenities.length + filters.drinkOptions.length
    );

    const selectCafe = (cafe) => {
        selectedCafe.value = cafe;
    };

    const clearSelection = () => {
        selectedCafe.value = null;
    };

    const clearFilters = () => {
        filters.search = '';
        filters.brewMethods = [];
        filters.amenities = [];
        filters.drinkOptions = [];
    };

    return {
        filters,
        filteredCafes,
        selectedCafe,
        hoveredCafe,
        totalActiveFilters,
        selectCafe,
        clearSelection,
        clearFilters,
    };
};
