<!-- AppLayout.vue -->
<template>
    <div class="min-h-screen bg-gray-100">

        <!-- Page Header -->
        <header class="bg-white shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <!-- Page Content -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <slot />
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500 text-sm">
                    Created for Technical Assessment - {{ new Date().getFullYear() }}
                </p>
            </div>
        </footer>
    </div>
</template>

<script setup>
defineProps({
    title: String
});
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>