<script setup>
import { ref, watch, computed } from "vue";

// Menggunakan modelValue untuk v-model
const props = defineProps({
    modelValue: String, // Nilai yang di-bind dengan v-model
    options: Array, // Opsi untuk select
    className: String, // Kelas CSS tambahan
});

const emit = defineEmits(["update:modelValue"]); // Menggunakan update:modelValue untuk v-model

// State lokal untuk nilai yang dipilih
const selectedValue = ref(props.modelValue);

// Komputasi untuk kelas
const classes = computed(
    () =>
        `rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 ${
            props.className || ""
        }`
);

// Melakukan sinkronisasi dengan nilai modelValue dari parent (form)
watch(
    () => props.modelValue,
    (newValue) => {
        selectedValue.value = newValue;
    }
);

// Fungsi untuk menangani perubahan nilai dan memancarkan event ke parent
const handleChange = (newValue) => {
    selectedValue.value = newValue;
    emit("update:modelValue", newValue); // Emit perubahan untuk sinkronisasi v-model
};
</script>

<template>
    <select
        :class="classes"
        v-model="selectedValue"
        @change="handleChange($event.target.value)"
    >
        <option
            v-for="(option, index) in options"
            :key="index"
            :value="option.value"
        >
            {{ option.label }}
        </option>
    </select>
</template>
