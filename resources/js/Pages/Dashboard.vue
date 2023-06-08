<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';

const pokemons = ref()
const pokemonInfo = ref()
const showTable = ref(false)
const showFavorites = ref(false)
const favorites = ref()
const showModal = ref(false)
const showInfo = ref(false)

const fetchList = async () => {
    const { data } = await axios.get('https://pokeapi.co/api/v2/pokemon?limit=100')
    pokemons.value = data.results;
    showTable.value = true;
}

const listFavorites = async () => {
    const { data } = await axios.post('/listfavorites');
    console.log(data);
    favorites.value = data
    showFavorites.value = true
}

onMounted(async () => {
    await fetchList();
    await listFavorites();
})

/**
 * Funcion para convertir el primer caracter de un string en mayuscula
 * @param {string} name
 */
const capitalize = (name) => {
    const str = name.charAt(0).toUpperCase() + name.slice(1);
    return str
}

const fetchFavorite = async (id_pokemon) => {
    const { data } = await axios.post('/savepokemon', {
        pokemon: id_pokemon
    })
    switch (data) {
        case "Favorite already exists":
            alert("Este pokemon ya esta en su lista de favoritos");
            break;
        case "Success":
            window.location.reload();
            break;
        default:
            break;
    }
}

const deleteFavorite = async (id_pokemon) => {
    const { data } = await axios.post('/deletePokemon', {
        pokemon: id_pokemon
    })
    if (data == 'Deleted') {
        window.location.reload();
    }
}

const toggleModal = async (name = null) => {
    if (name !== null) {
        console.log(pokemonInfo.value);
        const { data } = await axios.get(`https://pokeapi.co/api/v2/pokemon/${name}`)
        pokemonInfo.value = data
        console.log(pokemonInfo.value);
        showInfo.value = true
    }
    else {
        showInfo.value = false
    }
    showModal.value = !showModal.value
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bienvenido</h2>
        </template>

        <div class="py-12 flex">
            <div class="w-[50%]">
                <h3 class="pl-12 text-xl pb-2">Lista de pokemon</h3>
                <div v-if="showTable" class="bg-white shadow-sm ml-12 mr-12 max-w-7xl mx-auto sm:px-6 lg:px-8"
                    v-for="pokemon in pokemons">
                    <div class="overflow-hidden flex justify-between">
                        <div @click="toggleModal(pokemon.name)" class="py-2 text-lg hover:text-blue-600 cursor-pointer">{{
                            capitalize(pokemon.name) }}</div>
                        <button @click="fetchFavorite(pokemon.name)"
                            class="bg-green-500 rounded-xl p-2 m-2 shadow-2xl">Agregar a favoritos</button>
                    </div>
                </div>
            </div>
            <div v-if="showFavorites" class="w-[50%]">
                <h3 class="pl-12 text-xl pb-2">Favoritos</h3>
                <div class="bg-slate-200 p-2 rounded-md flex flex-col">
                    <div v-for="favorito in favorites" class="flex justify-between">
                        <div @click="toggleModal(favorito.ref_api)" class="hover:text-blue-600 cursor-pointer">{{ capitalize(favorito.ref_api) }}</div>
                        <button @click="deleteFavorite(favorito.ref_api)"
                            class="bg-red-500 text-white p-1 m-2 rounded-lg">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="toggleModal">
            <div class="p-2">
                Informacion del pokemon
                <div v-if="showInfo" class="flex">
                    <div>
                        <img :src="pokemonInfo.sprites.front_default" alt="" width="200">
                    </div>
                    <div class="flex flex-col">
                        <div>
                            <span class="text-lg font-medium">Id: </span>{{ pokemonInfo.id }}
                        </div>
                        <div>
                            <span class="text-lg font-medium">Nombre: </span>{{ capitalize(pokemonInfo.name) }}
                        </div>
                        <div>
                            <span class="text-lg font-medium">Peso: </span>{{ pokemonInfo.weight }}
                        </div>
                        <div>
                            <span class="text-lg font-medium">Altura: </span>{{ pokemonInfo.height }}
                        </div>
                        <div>
                            <span class="text-lg font-medium">Abilidades: </span>
                            <div v-for="ability in pokemonInfo.abilities">
                                {{ ability.ability.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </Modal>
    </AuthenticatedLayout>
</template>
