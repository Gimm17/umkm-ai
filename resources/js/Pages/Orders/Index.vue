<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import type { Order } from '@/types'
import OrderDetectedBanner from '@/Components/OrderDetectedBanner.vue'

const loading = ref(false)
const orders = ref<Order[]>([])
const stats = ref({
  total: 0,
  detected: 0,
  confirmed: 0,
  cancelled: 0,
  total_value: 0,
})

const filter = ref<'all' | 'detected' | 'confirmed' | 'cancelled'>('all')

const filteredOrders = computed(() => {
  if (filter.value === 'all') {
    return orders.value
  }
  return orders.value.filter(o => o.status === filter.value)
})

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount)
}

async function loadOrders() {
  loading.value = true
  try {
    const { data } = await axios.get(route('api.orders.index'))
    orders.value = data.data
  } catch (e) {
    console.error('Failed to load orders:', e)
  } finally {
    loading.value = false
  }
}

async function loadStats() {
  try {
    const { data } = await axios.get(route('api.orders.stats'))
    stats.value = data.data
  } catch (e) {
    console.error('Failed to load stats:', e)
  }
}

async function updateOrderStatus(orderId: number, status: 'confirmed' | 'cancelled') {
  try {
    await axios.patch(route('api.orders.update', orderId), { status })

    // Update local state
    const order = orders.value.find(o => o.id === orderId)
    if (order) {
      order.status = status
    }

    // Reload stats
    await loadStats()
  } catch (e) {
    console.error('Failed to update order:', e)
  }
}

onMounted(() => {
  loadOrders()
  loadStats()
})
</script>

<template>
  <Head title="Orders - UmkmAI" />

  <div class="min-h-screen bg-gradient-to-br from-[#FFF5F5] via-[#F0FFF4] to-[#EFF6FF] p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold bg-gradient-to-r from-[#FF6B6B] to-[#4ECDC4] bg-clip-text text-transparent">
        Orders 🛒
      </h1>
      <p class="text-gray-600 mt-1">Kelola order yang terdeteksi oleh AI</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
      <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 border-2 border-[#FFE8D6] shadow-sm">
        <div class="text-sm text-gray-500 mb-1">Total Order</div>
        <div class="text-2xl font-bold text-gray-900">{{ stats.total }}</div>
      </div>

      <div class="bg-gradient-to-br from-yellow-100 to-orange-100 rounded-2xl p-4 border-2 border-yellow-200 shadow-sm">
        <div class="text-sm text-yellow-700 mb-1">Perlu Konfirmasi</div>
        <div class="text-2xl font-bold text-yellow-800">{{ stats.detected }}</div>
      </div>

      <div class="bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl p-4 border-2 border-green-200 shadow-sm">
        <div class="text-sm text-green-700 mb-1">Dikonfirmasi</div>
        <div class="text-2xl font-bold text-green-800">{{ stats.confirmed }}</div>
      </div>

      <div class="bg-gradient-to-br from-red-100 to-rose-100 rounded-2xl p-4 border-2 border-red-200 shadow-sm">
        <div class="text-sm text-red-700 mb-1">Dibatalkan</div>
        <div class="text-2xl font-bold text-red-800">{{ stats.cancelled }}</div>
      </div>

      <div class="bg-gradient-to-br from-[#FF6B6B] to-[#FF8E8E] rounded-2xl p-4 border-2 border-[#FFE8D6] shadow-sm">
        <div class="text-sm text-white/80 mb-1">Total Value</div>
        <div class="text-xl font-bold text-white">{{ formatCurrency(stats.total_value) }}</div>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-2 mb-6 border-2 border-[#FFE8D6] shadow-sm">
      <div class="flex gap-2">
        <button
          v-for="tab in ['all', 'detected', 'confirmed', 'cancelled']"
          :key="tab"
          @click="filter = tab as any"
          :class="[
            'px-4 py-2 rounded-xl text-sm font-medium transition-all',
            filter === tab
              ? 'bg-gradient-to-r from-[#FF6B6B] to-[#FF8E8E] text-white shadow-md'
              : 'text-gray-600 hover:bg-gray-100',
          ]"
        >
          {{ tab === 'all' ? 'Semua' : tab.charAt(0).toUpperCase() + tab.slice(1) }}
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div
      v-if="loading"
      class="flex items-center justify-center py-12"
    >
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#FF6B6B]"></div>
    </div>

    <!-- Empty State -->
    <div
      v-else-if="filteredOrders.length === 0"
      class="flex flex-col items-center justify-center py-12 text-center bg-white/80 backdrop-blur-sm rounded-2xl border-2 border-[#FFE8D6]"
    >
      <div class="text-6xl mb-4">🛒</div>
      <p class="text-gray-600 font-medium">Belum ada order</p>
      <p class="text-sm text-gray-400 mt-1">Order yang terdeteksi AI akan muncul di sini</p>
    </div>

    <!-- Orders List -->
    <div
      v-else
      class="space-y-4"
    >
      <div
        v-for="order in filteredOrders"
        :key="order.id"
        class="bg-white/80 backdrop-blur-sm rounded-2xl border-2 border-[#FFE8D6] shadow-sm hover:shadow-md transition-all"
      >
        <OrderDetectedBanner :order="order" />

        <!-- Action Buttons -->
        <div
          v-if="order.status === 'detected'"
          class="flex gap-3 px-4 pb-4"
        >
          <button
            @click="updateOrderStatus(order.id, 'confirmed')"
            class="flex-1 px-4 py-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all"
          >
            ✅ Konfirmasi
          </button>
          <button
            @click="updateOrderStatus(order.id, 'cancelled')"
            class="flex-1 px-4 py-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-500 text-white font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all"
          >
            ❌ Batalkan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
