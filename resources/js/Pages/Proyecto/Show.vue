<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";

const { proyecto, movimientos, usuario } = usePage().props; // Assuming you have "proyecto" data passed from the backend.

const { user } = usePage().props;

const formatDate = (dateTime) => {
  return new Date(dateTime).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

function filtrarGastos(valor) {
  var names = document.getElementsByClassName('gasto-tabla-nombre');

  for (var i = 0; i < names.length; i++) {
    var name = names[i].textContent.trim(); // Get the content of the element and remove extra spaces
    
    if (name.includes(valor)) {
      names[i].parentNode.style.display = ''; // Show the parent element
    } else {
      names[i].parentNode.style.display = 'none'; // Hide the parent element
    }
  }
}

</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ proyecto.nombre }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div>
          <div class="flex">
            <div class="bg-white shadow-md rounded-md p-8">
              <h2 class="text-2xl font-semibold mb-4">{{ proyecto.nombre }}</h2>
              <p class="text-gray-600 mb-6">{{ proyecto.descripcion }}</p>
              <img
                :src="proyecto.imagen_url"
                alt="Imagen del proyecto"
                class="w-full rounded-md h-60 object-cover mb-6"
              />

              <div class="m-4 w-1/2">
                <h2 class="text-xl font-semibold mb-4">Gastos</h2>

                <GoogleChart :data="proyecto.chart" type="PieChart" />
              </div>

              <div class="m-4 w-1/2">
                <h2 class="text-xl font-semibold mb-4">Gastos por Usuario</h2>

                <GoogleChart :data="proyecto.user_chart" type="ColumnChart" />
              </div>

  <div class="m-4 mt-8 mb-8">
    <h2 class="text-xl font-semibold mb-4">Gastos por Usuario</h2>
    <input
          type="text"
          placeholder="Buscar gastos"
          class="w-full px-4 py-2 mb-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
          @keyup="filtrarGastos($event.target.value)"
        />
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-4 py-2">Factura Id</th>
            <th class="px-4 py-2">Fecha</th>
            <th class="px-4 py-2">Concepto</th>
            <th class="px-4 py-2">Observación</th>
            <th class="px-4 py-2">Importe</th>
            <th class="px-4 py-2">IVA</th>
            <th class="px-4 py-2">Importe Final</th>
            <th class="px-4 py-2">{{ proyecto.user.name }}</th>
              <th  v-for="(usuario, index) in proyecto.usuarios" class="px-4 py-2">{{ usuario.user.name }}</th>
            <th class="px-4 py-2">Editar Gasto</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(gasto, index) in proyecto.gastos" :key="index" :class="index % 2 === 0 ? 'bg-gray-50' : ''" class="gasto-tabla">
            <td class="px-4 py-2">{{ gasto.id_factura }}</td>
            <td class="px-4 py-2">{{ formatDate(gasto.created_at) }}</td>
            <td class="px-4 py-2 gasto-tabla-nombre">{{ gasto.nombre }}</td>
            <td class="px-4 py-2">{{ gasto.observacion }}</td>
            <td class="px-4 py-2">{{ gasto.cantidad }}</td>
            <td class="px-4 py-2">{{ gasto.anadir_iva ? 'X' : '' }}</td>
            <td class="px-4 py-2">{{ gasto.cantidad_iva == 0 ? gasto.cantidad :  gasto.cantidad_iva}}</td>
            <th class="px-4 py-2">{{ gasto.usuarios_id.includes(proyecto.user.id) ? 'X' : '' }}</th>

            <th class="px-4 py-2" v-for="(usuario, index) in proyecto.usuarios">{{ gasto.usuarios_id.includes(usuario.user.id) ? 'X' : '' }}</th>
            <th class="px-4 py-2">
              <Link
                  :href="route('proyecto.cantidades.update', { id: gasto.id })"
                  class="font-semibold text-gray-600 hover:text-green-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 m-4 mt-8 transition-colors duration-300 ease-in-out hover:border-b-2 border-transparent hover:border-green-900"
                >
                  Editar
                </Link>
            </th>
          </tr>
        </tbody>
      </table>
    </div>
  </div>


              <div>Presupuesto: {{ proyecto.presupuesto.toFixed(2) }} $</div>
              <br />
              <div
                v-for="(etiqueta, index) in proyecto.etiquetas"
                :key="index"
                class="row"
              >
                <div class="label">
                  {{ etiqueta.etiqueta }}: {{ etiqueta.cantidad.toFixed(2) }}$ -
                  {{ ((etiqueta.cantidad / proyecto.presupuesto) * 100).toFixed(2) }}%
                </div>
                <br />
                <hr />
              </div>
              <br />
              <div>Total Gastado: {{ proyecto.gastado.toFixed(2) }} $</div>
              <div>Total Restante: {{ proyecto.restante.toFixed(2) }} $</div>

              <div class="m-8" v-if="usuario.is_admin">
                <Link
                  :href="route('proyecto.cantidades.edit', { id: proyecto.id })"
                  class="font-semibold text-gray-600 hover:text-green-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 m-4 mt-8 transition-colors duration-300 ease-in-out hover:border-b-2 border-transparent hover:border-green-900"
                >
                  Añadir gasto
                </Link>

                <Link
                  :href="route('proyecto.usuarios.index', { id: proyecto.id })"
                  class="font-semibold text-gray-600 hover:text-green-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 m-4 mt-8 transition-colors duration-300 ease-in-out hover:border-b-2 border-transparent hover:border-green-900"
                >
                  Administrar Usuarios
                </Link>

                <Link
                  :href="route('proyecto.edit', { id: proyecto.id })"
                  class="font-semibold text-gray-600 hover:text-green-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 m-4 mt-8 transition-colors duration-300 ease-in-out hover:border-b-2 border-transparent hover:border-green-900"
                >
                  Editar Proyecto
                </Link>

                <Link
                  :href="route('proyecto.delete', { id: proyecto.id })"
                  method="delete"
                  as="button"
                  class="inline-block bg-red-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50 transition duration-300 ease-in-out m-4 mt-8"
                >
                  Delete Proyecto
                </Link>
              </div>
            </div>

            <div>
              <div
                class="bg-white shadow-md rounded-md p-8 flex-1 mx-4 mb-4 overflow-auto max-h-50vh"
              >
                <h2 class="text-xl font-semibold mb-4">Usuarios Asignados</h2>
                <ul>
                  <li>{{ proyecto.user.name }} - Administrador</li>

                  <li v-for="(user, index) in proyecto.usuarios" :key="index">
                    {{ user.user.name }} - <span v-if="user.is_admin"> Administrador</span
                    ><span v-else>Usuario</span>
                  </li>
                </ul>
              </div>
              <!-- Movimientos (scrollable) -->
              <div
                class="bg-white shadow-md rounded-md p-8 flex-1 mx-4 mb-4 overflow-auto max-h-50vh"
              >
                <h2 class="text-xl font-semibold mb-4">Movimientos</h2>
                <ul>
                  <li
                    v-for="(movimiento, index) in movimientos"
                    :key="index"
                    class="mb-4"
                  >
                    {{ movimiento.user.name }} - {{ movimiento.valor }} -
                    {{ new Date(movimiento.created_at).toLocaleDateString() }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
