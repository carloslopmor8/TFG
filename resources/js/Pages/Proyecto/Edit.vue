<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const { proyecto } = usePage().props;

const form = useForm({
    nombre: proyecto.nombre,
    descripcion: proyecto.descripcion,
    presupuesto: proyecto.presupuesto,
});

let selectedFile = null;

const handleFileChange = (event) => {
    form.imagen_url = event.target.files[0];
};

const submit = () => {
  const formData = new FormData();
  formData.append('nombre', form.nombre);
  formData.append('descripcion', form.descripcion);
  formData.append('presupuesto', form.presupuesto);

  // Add the selected file to the form data
  form.post(route('proyecto.update', { id: proyecto.id }), {
    data: formData,
  });

};


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar proyecto</h2>
            <Link

          :href="route('proyecto.show', { id: proyecto.id })"
          class="font-semibold text-gray-600 hover:text-gray-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 m-8 mt-8"
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
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div>
                        <InputLabel for="nombre" value="Nombre del Proyecto" />

                        <TextInput
                            id="nombre"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.nombre"
                            autofocus
                            autocomplete="nombre"
                        />

                        <InputError class="mt-2" :message="form.errors.nombre" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="descripcion" value="Descripcion del Proyecto" />

                        <textarea name="descripcion" id="descripcion" cols="60" rows="10" v-model="form.descripcion"></textarea>

                        <InputError class="mt-2" :message="form.errors.descripcion" />
                    </div>

                    <!-- <div class="mt-4">
                        <InputLabel for="imagen_url" value="Imagen" />

                        <TextInput
                            id="imagen_url"
                            name="imagen_url"
                            type="file"
                            class="mt-1 block w-full"
                            @change="handleFileChange"
                            autocomplete="new-password"
                        />


                        <InputError class="mt-2" :message="form.errors.imagen_url" />
                    </div> -->

                    <div class="mt-4">
                        <InputLabel for="presupuesto" value="Presupuesto" />

                        <TextInput
                            id="presupuesto"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.presupuesto"
                            autocomplete="new-password"
                        />

                        <InputError class="mt-2" :message="form.errors.presupuesto" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Editar!
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
