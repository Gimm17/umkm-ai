<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-4">
                Welcome to UmkmAI 🤖
            </h1>
            <p class="text-center text-gray-600 mb-6">
                AI-powered omnichannel inbox for Indonesian SMBs
            </p>
            <div class="space-y-3">
                <div class="flex items-center text-sm text-gray-600">
                    <span class="text-green-500 mr-2">✓</span>
                    Laravel 12 + Inertia.js + Vue 3
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <span class="text-green-500 mr-2">✓</span>
                    Tailwind CSS configured
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <span class="text-green-500 mr-2">✓</span>
                    Ready for development
                </div>
            </div>
        </div>
    </div>
</template>
