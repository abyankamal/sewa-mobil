<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

// Mendefinisikan props untuk data mobil yang diterima dari server
const props = defineProps({
    cars: Array,
});

// State untuk pencarian dan filter
const searchQuery = ref(""); // Pencarian berdasarkan merek, model, atau nomor plat
const filterStatus = ref(""); // Filter berdasarkan ketersediaan (tersedia atau tidak)

// Computed untuk memfilter dan mencari mobil
const filteredCars = computed(() => {
    return props.cars.filter((car) => {
        const matchesSearch =
            car.brand.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            car.model.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            car.license_plate
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase());

        const matchesFilter =
            filterStatus.value === "" ||
            (filterStatus.value === "available" && car.is_available) ||
            (filterStatus.value === "unavailable" && !car.is_available);

        return matchesSearch && matchesFilter;
    });
});
</script>

<template>
    <Head title="Daftar Mobil" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Halaman Daftar Mobil
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4 mx-4">
                    <div class="flex items-center space-x-4">
                        <!-- Input pencarian -->
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Cari berdasarkan merek, model, atau nomor plat"
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <!-- Dropdown filter status -->
                        <select
                            v-model="filterStatus"
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="available">Tersedia</option>
                            <option value="unavailable">Tidak Tersedia</option>
                        </select>
                    </div>
                    <Link
                        :href="route('addCar')"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Tambah Mobil
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mt-8 flow-root">
                            <div
                                class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
                            >
                                <div
                                    class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                                >
                                    <table
                                        class="min-w-full divide-y divide-gray-300"
                                    >
                                        <thead>
                                            <tr
                                                class="divide-x divide-gray-400"
                                            >
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                                >
                                                    No
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Merek
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Model
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Nomor Plat
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Biaya Rental
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Status
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Loop melalui mobil yang telah difilter -->
                                            <tr
                                                v-if="filteredCars.length > 0"
                                                v-for="(
                                                    car, index
                                                ) in filteredCars"
                                                :key="car.id"
                                                class="divide-x divide-gray-400"
                                            >
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0"
                                                >
                                                    {{ index + 1 }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ car.brand }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ car.model }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ car.license_plate }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ car.rental_rate }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{
                                                        car.is_available
                                                            ? "Tersedia"
                                                            : "Tidak Tersedia"
                                                    }}
                                                </td>
                                                <td
                                                    v-if="car.is_available"
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 justify-between"
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'rentForm',
                                                                car.id
                                                            )
                                                        "
                                                        class="rounded-md bg-indigo-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                                                        >Sewa</Link
                                                    >
                                                </td>
                                                <td
                                                    v-else
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 justify-between"
                                                >
                                                    ---
                                                </td>
                                            </tr>
                                            <!-- Jika tidak ada mobil yang tersedia setelah filter -->
                                            <tr v-else class="mx-auto">
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm rounded-md bg-red-500 text-white text-center"
                                                    colspan="100%"
                                                >
                                                    Mobil Tidak Ditemukan
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
