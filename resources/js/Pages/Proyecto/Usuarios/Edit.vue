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
  usuario: "",
});

const submit = () => {
  form.post(
    route("proyecto.add.usuario", { id: proyecto.id }),
    /* Optional data to be sent in the request, if needed */
    /* {
        someKey: someValue,
    }, */
    {
      /* Options object containing callbacks or other configuration */
      onFinish: () => form.reset("usuario"),
    }
  );
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edita Usuarios en {{ proyecto.nombre }}
      </h2>
      <Link

          :href="route('proyecto.show', { id: proyecto.id })"
          class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 m-8 mt-8"
          >Atrás</Link
        >
    </template>

    <div v-if="$page.props.flash.error" class="bg-red-500 p-4 rounded-lg text-white m-4">
      <p class="text-lg">{{ $page.props.flash.error }}</p>
    </div>

    <div
      v-if="$page.props.flash.success"
      class="bg-green-500 p-4 rounded-lg text-white m-4"
    >
      <p class="text-lg">{{ $page.props.flash.success }}</p>
    </div>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Loop through the usuarios array and display each usuario in a cool row -->
        <div
          v-for="usuario in proyecto.usuarios"
          :key="usuario.id"
          class="bg-white shadow-md rounded-md p-4 cursor-pointer transition-transform duration-300 transform hover:scale-105 flex items-center justify-between"
          :class="{ 'bg-gray-100': usuario.isHovered }"
          @mouseenter="usuario.isHovered = true"
          @mouseleave="usuario.isHovered = false"
        >
          <div class="text-lg font-semibold mb-4">{{ usuario.user.name }}</div>
          <div class="text-lg font-semibold mb-4">{{ usuario.user.email }}</div>

          <!-- Add a delete button for each usuario -->
          
          <Link
            :href="route('proyecto.usuarios.edit', { id: proyecto.id, usuario: usuario.id })"
            ><button class="text-blue-500">Gestionar</button></Link
          >

        </div>
      </div>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="submit">
          <div>
            <InputLabel for="usuario" value="Nuevo Usuario" />

            <TextInput
              id="usuario"
              type="text"
              class="mt-1 block w-full"
              v-model="form.usuario"
              placeholder="Email del usuario"
              autofocus
              autocomplete="usuario"
            />

            <InputError class="mt-2" :message="form.errors.usuario" />
          </div>

          <div class="flex items-center justify-end mt-4">
            <PrimaryButton
              class="ml-4"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Añadir usuario!
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
