<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";

const { proyecto } = usePage().props; // Access the "proyecto" data passed from the controller.

const form = useForm({
  /*  user_id: '', */
  etiqueta: "",
});

const submit = () => {
  form.post(
    route("proyecto.add.etiqueta", { id: proyecto.id }),
    /* Optional data to be sent in the request, if needed */
    /* {
        someKey: someValue,
    }, */
    {
      /* Options object containing callbacks or other configuration */
      onFinish: () => form.reset('etiqueta'),
    }
  );
};

</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edita Etiquetas en {{ proyecto.nombre }}
      </h2>
      <Link

          :href="route('proyecto.show', { id: proyecto.id })"
          class="font-semibold text-gray-600 hover:text-gray-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 m-8 mt-8"
          >Atrás</Link
        >
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Loop through the etiquetas array and display each etiqueta in a cool row -->
        <div
          v-for="etiqueta in proyecto.etiquetas"
          :key="etiqueta.id"
          class="bg-white shadow-md rounded-md p-4 cursor-pointer transition-transform duration-300 transform hover:scale-105 flex items-center justify-between"
          :class="{ 'bg-gray-100': etiqueta.isHovered }"
          @mouseenter="etiqueta.isHovered = true"
          @mouseleave="etiqueta.isHovered = false"
        >
          <div class="text-lg font-semibold mb-4">{{ etiqueta.etiqueta }}</div>
          <!-- Add a delete button for each etiqueta -->
          <button class="text-red-500">Delete</button>
        </div>
      </div>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="submit">
          <div>
            <InputLabel for="etiqueta" value="Nueva Etiqueta" />

            <TextInput
              id="etiqueta"
              type="text"
              class="mt-1 block w-full"
              v-model="form.etiqueta"
              autofocus
              autocomplete="etiqueta"
            />

            <InputError class="mt-2" :message="form.errors.etiqueta" />
          </div>

          <div class="flex items-center justify-end mt-4">
            <PrimaryButton
              class="ml-4"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Añadir etiqueta!
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
