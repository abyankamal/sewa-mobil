<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectBox from "@/Components/SelectBox.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    brands: Array, // Data merek dari controller
    models: Object, // Data model dari controller (dipetakan berdasarkan merek)
});

// Membuat state form dengan useForm untuk binding
const form = useForm({
    brand: "", // Merek yang dipilih
    model: "", // Model yang dipilih, bergantung pada merek
    license_plate: "", // Nomor plat mobil
    rental_rate: "", // Tarif sewa per hari
});

// Mengatur ulang opsi model setiap kali merek berubah
const updateModelOptions = () => {
    form.model = ""; // Reset model ketika merek berubah
};

const onSubmit = (e) => {
    e.preventDefault();

    form.post(route("addCar"), {
        preserveScroll: true,
        onSuccess: () => {
            alert("Mobil berhasil ditambahkan");
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};
</script>

<template>
    <Head title="Halaman Tambah Mobil" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Mobil
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="max-w-xl">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    Tambah Mobil
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Silahkan Masukan Data Mobil
                                </p>
                            </header>

                            <form
                                @submit.prevent="onSubmit"
                                class="mt-6 space-y-6"
                            >
                                <!-- Dropdown untuk Merek -->
                                <div>
                                    <InputLabel for="brand" value="Merek" />
                                    <SelectBox
                                        v-model="form.brand"
                                        :options="
                                            brands.map((brand) => ({
                                                label: brand,
                                                value: brand,
                                            }))
                                        "
                                        @change="updateModelOptions"
                                        class="mt-1 block w-full"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.brand"
                                    />
                                </div>

                                <!-- Dropdown untuk Model -->
                                <div>
                                    <InputLabel for="model" value="Model" />
                                    <SelectBox
                                        v-model="form.model"
                                        :options="
                                            (models[form.brand] || []).map(
                                                (model) => ({
                                                    label: model,
                                                    value: model,
                                                })
                                            )
                                        "
                                        class="mt-1 block w-full"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.model"
                                    />
                                </div>

                                <!-- Nomor Plat -->
                                <div>
                                    <InputLabel
                                        for="license_plate"
                                        value="Nomor Plat"
                                    />
                                    <TextInput
                                        id="license_plate"
                                        v-model="form.license_plate"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Contoh: B 1234 XYZ"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.license_plate"
                                    />
                                </div>

                                <!-- Tarif Sewa per Hari -->
                                <div>
                                    <InputLabel
                                        for="rental_rate"
                                        value="Tarif Sewa per Hari"
                                    />
                                    <TextInput
                                        id="rental_rate"
                                        v-model="form.rental_rate"
                                        type="number"
                                        class="mt-1 block w-full"
                                        placeholder="Tarif Sewa"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.rental_rate"
                                    />
                                </div>

                                <!-- Tombol Simpan -->
                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing"
                                        >Simpan</PrimaryButton
                                    >
                                    <Transition
                                        enter-active-class="transition ease-in-out"
                                        enter-from-class="opacity-0"
                                        leave-active-class="transition ease-in-out"
                                        leave-to-class="opacity-0"
                                    >
                                        <p
                                            v-if="form.recentlySuccessful"
                                            class="text-sm text-gray-600"
                                        >
                                            Berhasil disimpan.
                                        </p>
                                    </Transition>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
