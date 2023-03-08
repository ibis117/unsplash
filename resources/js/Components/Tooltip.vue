<template>
    <div class="group relative inline-block text-blue-500 underline hover:text-red-500 duration-300">
        <slot />
        <span
            v-show="text"
            class="absolute hidden group-hover:flex -top-8 px-2 py-1 bg-gray-700 rounded-lg text-center text-white text-sm">{{ text }}</span>
    </div>
</template>

<script setup>

defineProps({
    text: {
        type:String,
        default: ''
    }
})
</script>

<style scoped>

</style>
