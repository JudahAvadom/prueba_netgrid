<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

onMounted(() => {

})
</script>

<template>

    <Head title="Favoritos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bienvenido</h2>
        </template>
    </AuthenticatedLayout>

</template>
