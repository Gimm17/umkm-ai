<script setup lang="ts">
import { computed } from 'vue'
import type { Order } from '@/types'

const props = defineProps<{
  order: Order
}>()

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount)
}

const statusConfig = computed(() => {
  switch (props.order.status) {
    case 'detected':
      return {
        label: 'Order Terdeteksi',
        icon: '🛒',
        bgClass: 'bg-gradient-to-r from-yellow-100 to-orange-100 dark:from-yellow-900/30 dark:to-orange-900/30',
        textClass: 'text-yellow-800 dark:text-yellow-200',
        borderClass: 'border-yellow-200 dark:border-yellow-800',
      }
    case 'confirmed':
      return {
        label: 'Order Dikonfirmasi',
        icon: '✅',
        bgClass: 'bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30',
        textClass: 'text-green-800 dark:text-green-200',
        borderClass: 'border-green-200 dark:border-green-800',
      }
    case 'cancelled':
      return {
        label: 'Order Dibatalkan',
        icon: '❌',
        bgClass: 'bg-gradient-to-r from-red-100 to-rose-100 dark:from-red-900/30 dark:to-rose-900/30',
        textClass: 'text-red-800 dark:text-red-200',
        borderClass: 'border-red-200 dark:border-red-800',
      }
  }
})
</script>

<template>
  <div
    :class="[
      'relative overflow-hidden rounded-2xl border-2 p-4 shadow-sm transition-all hover:shadow-md',
      statusConfig.bgClass,
      statusConfig.borderClass,
    ]"
  >
    <!-- Background decoration -->
    <div class="absolute top-0 right-0 -mt-2 -mr-2 h-16 w-16 rounded-full bg-white opacity-20"></div>

    <!-- Header -->
    <div class="relative flex items-center gap-2 mb-3">
      <span class="text-2xl">{{ statusConfig.icon }}</span>
      <span
        :class="[
          'text-sm font-bold uppercase tracking-wide',
          statusConfig.textClass,
        ]"
      >
        {{ statusConfig.label }}
      </span>
    </div>

    <!-- Order Items -->
    <div class="relative space-y-2 mb-3">
      <div
        v-for="(item, index) in order.items"
        :key="index"
        class="flex items-center justify-between text-sm"
      >
        <span class="font-medium">
          {{ item.qty }}x {{ item.name }}
        </span>
        <span class="text-gray-600 dark:text-gray-300">
          {{ formatCurrency(item.price * item.qty) }}
        </span>
      </div>
    </div>

    <!-- Total -->
    <div class="relative flex items-center justify-between pt-3 border-t border-current border-opacity-20">
      <span
        :class="['text-sm font-semibold', statusConfig.textClass]"
      >
        Total Estimate
      </span>
      <span
        :class="['text-lg font-bold', statusConfig.textClass]"
      >
        {{ formatCurrency(order.total_estimate) }}
      </span>
    </div>

    <!-- Shipping Address (jika ada) -->
    <div
      v-if="order.shipping_address"
      class="relative mt-3 pt-3 border-t border-current border-opacity-20"
    >
      <div class="text-xs font-semibold mb-1 opacity-70">
        📍 Alamat Pengiriman
      </div>
      <div class="text-sm opacity-90">
        {{ order.shipping_address }}
      </div>
    </div>
  </div>
</template>
