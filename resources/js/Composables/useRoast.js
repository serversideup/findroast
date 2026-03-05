import { useForm } from '@inertiajs/vue3';

const form = useForm({
    id: null,
    name: '',
    url: '',
    price: '',
    primary_image: '',
    new_primary_image: '',
    in_stock: null,
    flavor_notes: [],
    processes: [],
    countries: [],
    varieties: [],
    elevations: []
});

export const useRoast = () => {

    const setRoast = (data) => {
        form.id = data.id;
        form.name = data.name;
        form.url = data.url;
        form.price = data.price;
        form.primary_image = data.primary_image;
        form.new_primary_image = '';
        form.in_stock = data.in_stock;
        form.flavor_notes = data.flavor_notes;
        form.processes = data.processes;
        form.countries = data.countries;
        form.varieties = data.varieties;
        form.elevations = data.elevations;
    }
    
    return {
        form,
        setRoast
    }
}