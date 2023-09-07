<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import GuestLayout from "@/Layouts/GuestLayout.vue";
import Checkbox from '@/Components/Checkbox.vue';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const { proyecto } = usePage().props;

const selectedOption = ref("cambio"); // Set the default selected option


const cambio_form = useForm({
  factura_id: "",
  nombre: "",
  observacion: "",
  form: "cambio",
  etiqueta: "",
  cantidad: "",
  anadir_iva: false,
  quien: [],
  fecha: new Date().toISOString().substr(0, 10),

});

const cambio_submit = () => {
  cambio_form.post(route("proyecto.save.cantidad", { id: proyecto.id }));
};

</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Añadir gasto en {{ proyecto.nombre }}
      </h2>
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
    <div class="py-12 bg-gray-300">
      <div class="bg-gray-100 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-md p-6 w-96">
          
          <div  class="mt-6">
            <form @submit.prevent="cambio_submit">
              <div class="mb-2">
                <InputLabel for="factura_id" value="Factura ID" />

                <TextInput
                  id="factura_id"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="cambio_form.factura_id"
                  autofocus
                  autocomplete="factura_id"
                />

                <InputError class="mt-2" :message="cambio_form.errors.factura_id" />
              </div>
              <div class="mb-2">
                <InputLabel for="nombre" value="Nombre*" />

                <TextInput
                  id="nombre"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="cambio_form.nombre"
                  autofocus
                  autocomplete="nombre"
                />

                <InputError class="mt-2" :message="cambio_form.errors.nombre" />
              </div>
              <div class="mb-2">
                  <InputLabel for="observacion" value="Observacion" />

                  <textarea name="observacion" id="observacion" cols="35" rows="10" v-model="cambio_form.observacion"></textarea>

                  <InputError class="mt-2" :message="cambio_form.errors.observacion" />
              </div>

              <div class="mb-2">
                <InputLabel for="cantidad" value="Cantidad*" />

                <TextInput
                  id="cantidad"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="cambio_form.cantidad"
                  autofocus
                  autocomplete="cantidad"
                />

                <InputError class="mt-2" :message="cambio_form.errors.cantidad" />
              </div>

              <div class="block my-4">
                  <label class="flex items-center">
                      <Checkbox name="anadir_iva" v-model:checked="cambio_form.anadir_iva" />
                      <span class="ml-2 text-sm text-gray-600">Añadir IVA</span>
                  </label>
              </div>

              <!-- <div class="mb-2">
                <InputLabel for="cantidad_iva" value="Cantidad con IVA" />

                <TextInput
                  id="cantidad_iva"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="cambio_form.cantidad_iva"
                  autofocus
                  autocomplete="cantidad_iva"
                />

                <InputError class="mt-2" :message="cambio_form.errors.cantidad_iva" />
              </div> -->

              <div class="mb-2">
                <InputLabel for="quien" value="Quien realiza el movimiento*" />

                <select
                  class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-900 focus:outline-none focus:ring focus:ring-indigo-200"
                  v-model="cambio_form.quien" multiple
                >
                  <option :value="proyecto.user.id" >
                      {{ proyecto.user.name }} (Administrador)
                  </option>
                  <option
                    v-for="usuario in proyecto.usuarios"
                    :key="usuario.id"
                    :value="usuario.user.id"
                  >
                    {{ usuario.user.name }}
                  </option>
                </select>

                <InputError class="mt-2" :message="cambio_form.errors.quien" />
              </div>


              <div class="mb-2">
                <InputLabel for="quien" value="Fecha del movimiento*" />

                <TextInput
                  id="cantidad"
                  type="date"
                  class="mt-1 block w-full"
                  v-model="cambio_form.fecha"
                  autofocus
                  autocomplete="cantidad"
                />


                <InputError class="mt-2" :message="cambio_form.errors.fecha" />
              </div>

              <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                  class="ml-4"
                  :class="{ 'opacity-25': cambio_form.processing }"
                  :disabled="cambio_form.processing"
                >
                  Añadir gasto
                </PrimaryButton>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"></div>
    </div>
  </AuthenticatedLayout>
</template>
