<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';

const pokemons = ref()
const showTable = ref(false)

onMounted(async()=>{
    const {data} = await axios.get('https://pokeapi.co/api/v2/pokemon?limit=100')
    pokemons.value = data.results;
    showTable.value = true;
    console.log(pokemons.value);
})

/**
 * Funcion para convertir el primer caracter de un string en mayuscula
 * @param {string} name
 */
const capitalize = (name) => {
    const str = name.charAt(0).toUpperCase() + name.slice(1);
    return str
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bienvenido</h2>
        </template>

        <div class="py-12">
            <h3>Lista de pokemon</h3>
            <div v-if="showTable" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-for="pokemon in pokemons">
                    <div class="p-2 text-lg">{{ capitalize(pokemon.name) }}</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
