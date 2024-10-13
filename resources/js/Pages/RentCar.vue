<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Transition } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    cars: {
        type: Object,
        required: true,
    },
});

// Cek apakah `car` didefinisikan dengan benar
if (!props.cars) {
    console.error("Data car tidak tersedia");
}

// Gunakan `useForm` dari Inertia.js untuk menangani form
const form = useForm({
    car_id: props.cars.id, // ID mobil yang disewa
    start_date: "", // Tanggal mulai sewa
    end_date: "", // Tanggal selesai sewa
});

// Fungsi untuk mengirim form penyewaan ke backend
const onSubmit = (e) => {
    e.preventDefault();

    form.post(route("rentCar"), {
        preserveScroll: true,
        onSuccess: () => {
            alert("Penyewaan berhasil!");
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};
</script>

<template>
    <Head title="Form Penyewaan Mobil" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Penyewaan Mobil
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mt-8 flow-root">
                            <div
                                class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
                            >
                                <div
                                    class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                                >
                                    <section class="max-w-xl">
                                        <header>
                                            <h2
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                Penyewaan Mobil
                                            </h2>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            >
                                                Silakan masukkan detail
                                                penyewaan.
                                            </p>
                                        </header>

                                        <form
                                            @submit.prevent="onSubmit"
                                            class="mt-6 space-y-6"
                                        >
                                            <!-- Bagian Informasi Mobil -->
                                            <div>
                                                <InputLabel
                                                    for="car"
                                                    value="Mobil yang Disewa"
                                                />
                                                <p class="mt-1 block w-full">
                                                    <strong>{{
                                                        cars.brand
                                                    }}</strong>
                                                    - {{ cars.model }} ({{
                                                        cars.license_plate
                                                    }})
                                                </p>
                                            </div>

                                            <!-- Input Tanggal Mulai Sewa -->
                                            <div>
                                                <InputLabel
                                                    for="start_date"
                                                    value="Tanggal Mulai Sewa"
                                                />
                                                <TextInput
                                                    id="start_date"
                                                    type="date"
                                                    class="mt-1 block w-full"
                                                    v-model="form.start_date"
                                                    required
                                                />
                                                <InputError
                                                    class="mt-2"
                                                    :message="
                                                        form.errors.start_date
                                                    "
                                                />
                                            </div>

                                            <!-- Input Tanggal Selesai Sewa -->
                                            <div>
                                                <InputLabel
                                                    for="end_date"
                                                    value="Tanggal Selesai Sewa"
                                                />
                                                <TextInput
                                                    id="end_date"
                                                    type="date"
                                                    class="mt-1 block w-full"
                                                    v-model="form.end_date"
                                                    required
                                                />
                                                <InputError
                                                    class="mt-2"
                                                    :message="
                                                        form.errors.end_date
                                                    "
                                                />
                                            </div>

                                            <!-- Tombol Simpan -->
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <PrimaryButton
                                                    :disabled="form.processing"
                                                    >Sewa</PrimaryButton
                                                >

                                                <Transition
                                                    enter-active-class="transition ease-in-out"
                                                    enter-from-class="opacity-0"
                                                    leave-active-class="transition ease-in-out"
                                                    leave-to-class="opacity-0"
                                                >
                                                    <p
                                                        v-if="
                                                            form.recentlySuccessful
                                                        "
                                                        class="text-sm text-gray-600"
                                                    >
                                                        Penyewaan berhasil
                                                        disimpan.
                                                    </p>
                                                </Transition>
                                            </div>
                                        </form>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
