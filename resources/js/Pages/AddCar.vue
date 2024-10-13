<script setup>
import SelectBox from "@/Components/SelectBox.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import InputError from "vendor/laravel/breeze/stubs/inertia-react/resources/js/Components/InputError";
import InputLabel from "vendor/laravel/breeze/stubs/inertia-react/resources/js/Components/InputLabel";
import TextInput from "vendor/laravel/breeze/stubs/inertia-react/resources/js/Components/TextInput";

const form = useForm({
    brand: "",
    model: "",
    license_plate: "",
    rental_rate: "",
});

// Define a method to handle the change event from the SelectBox

const onSubmit = (e) => {
    e.preventDefault();

    form.post(route("courses.store"), {
        preserveScroll: true,
        onSuccess: () => {
            alert("Courses Sucess Created");
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

        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    className="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div className="p-6 text-gray-900">
                        <div className="mt-8 flow-root">
                            <div
                                className="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
                            >
                                <div
                                    className="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                                >
                                    <section className="max-w-xl">
                                        <header>
                                            <h2
                                                className="text-lg font-medium text-gray-900"
                                            >
                                                Tambah Mobil
                                            </h2>

                                            <p
                                                className="mt-1 text-sm text-gray-600"
                                            >
                                                Silahkan Masukan Data Mobil
                                            </p>
                                        </header>

                                        <form
                                            @submit.prevent="onSubmit"
                                            class="mt-6 space-y-6"
                                        >
                                            <div>
                                                <InputLabel
                                                    for="name"
                                                    value="Judul"
                                                />

                                                <TextInput
                                                    id="name"
                                                    type="text"
                                                    class="mt-1 block w-full"
                                                    v-model="form.title"
                                                    required
                                                    autofocus
                                                    autocomplete="name"
                                                />

                                                <InputError
                                                    class="mt-2"
                                                    :message="form.errors.title"
                                                />
                                            </div>

                                            <div>
                                                <InputLabel
                                                    for="description"
                                                    value="Deskripsi"
                                                />

                                                <TextArea
                                                    id="name"
                                                    type="text"
                                                    class="mt-1 block w-full"
                                                    v-model="form.description"
                                                    required
                                                    autofocus
                                                    autocomplete="name"
                                                />

                                                <InputError
                                                    class="mt-2"
                                                    :message="
                                                        form.errors.description
                                                    "
                                                />
                                            </div>

                                            <div>
                                                <InputLabel
                                                    for="description"
                                                    value="Guru Pengampu"
                                                />

                                                <SelectBox
                                                    id="role"
                                                    :options="teachers"
                                                    v-model="form.teacher_id"
                                                    @change="
                                                        handleSelectionChange
                                                    "
                                                    className="mt-1 block w-full"
                                                />

                                                <InputError
                                                    class="mt-2"
                                                    :message="
                                                        form.errors.description
                                                    "
                                                />
                                            </div>

                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <PrimaryButton
                                                    :disabled="form.processing"
                                                    >Simpan</PrimaryButton
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
                                                        Saved.
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
