<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";


const { proyectos } = usePage().props; // Access the "proyectos" data passed from the controller.

const searchTerm = ref("");

// Computed property to filter projects based on the search term

const form = useForm({
  search: "",

});

const submit = () => {
  form.get(route("dashboard"));
};

</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proyectos:</h2>
        <Link
          :href="route('proyecto.create')"
          class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1 focus:ring-offset-white"
        >
          Crear un proyecto
        </Link>
      </div>
      <div class="py-4">
      <form @submit.prevent="submit" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 flex"
>
        <input
          v-model="form.search"
          type="text"
          placeholder="Buscar proyectos"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"

        />
        <PrimaryButton
          class="ml-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Buscar
        </PrimaryButton>
      </form>
  </div>
    </template>

    

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-4">
          <!-- Use a for loop to iterate over the "proyectos" -->
          <a
            v-for="proyecto in proyectos"
            :key="proyecto.id"
            :href="route('proyecto.show', { id: proyecto.id })"
            class="bg-white shadow-md rounded-md p-4 cursor-pointer transition-transform duration-300 transform hover:scale-105"
          >
            <!-- Display the proyecto data in a cool card format -->
            <h3 class="text-lg font-semibold mb-2">{{ proyecto.nombre }}</h3>
            <p class="text-gray-600 mb-4">{{ proyecto.descripcion }}</p>
            <!-- src="http://presupuestos2.test/storage/files/andrew%20tate.jpg" -->
            <img
              :src="proyecto.imagen_url"
              alt="Imagen del proyecto"
              class="w-full rounded-md h-40 object-cover"
            />
          </a>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
