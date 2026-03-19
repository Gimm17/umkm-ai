<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

const loading = ref(false)
const saving = ref(false)
const testing = ref<'whatsapp' | 'instagram' | null>(null)
const testResult = ref<{ success: boolean; message: string } | null>(null)

const settings = ref({
  ai_prompt: {
    business_name: '',
    business_description: '',
    product_list: '',
    custom_prompt: '',
  },
  channel: {
    whatsapp_phone_number_id: '',
    whatsapp_access_token: '',
    whatsapp_verify_token: '',
    whatsapp_app_secret: '',
    instagram_access_token: '',
    instagram_verify_token: '',
    instagram_app_secret: '',
  },
  general: {
    anthropic_api_key: '',
  },
})

const tabs = ref<'ai_prompt' | 'channel' | 'general'>('ai_prompt')

async function loadSettings() {
  loading.value = true
  try {
    const { data } = await axios.get(route('api.settings.index'))
    settings.value = data.data
  } catch (e) {
    console.error('Failed to load settings:', e)
  } finally {
    loading.value = false
  }
}

async function saveSettings() {
  saving.value = true
  testResult.value = null

  try {
    await axios.post(route('api.settings.update'), settings.value)

    testResult.value = {
      success: true,
      message: 'Pengaturan berhasil disimpan! ✅',
    }

    setTimeout(() => {
      testResult.value = null
    }, 3000)
  } catch (e) {
    testResult.value = {
      success: false,
      message: 'Gagal menyimpan pengaturan ❌',
    }
  } finally {
    saving.value = false
  }
}

async function testConnection(channel: 'whatsapp' | 'instagram') {
  testing.value = channel
  testResult.value = null

  try {
    const routeName = channel === 'whatsapp'
      ? 'api.settings.test-whatsapp'
      : 'api.settings.test-instagram'

    const { data } = await axios.post(routeName)

    testResult.value = {
      success: true,
      message: `Koneksi ${channel === 'whatsapp' ? 'WhatsApp' : 'Instagram'} berhasil! ✅`,
    }

    setTimeout(() => {
      testResult.value = null
    }, 3000)
  } catch (e) {
    testResult.value = {
      success: false,
      message: `Koneksi ${channel === 'whatsapp' ? 'WhatsApp' : 'Instagram'} gagal ❌`,
    }
  } finally {
    testing.value = null
  }
}

onMounted(() => {
  loadSettings()
})
</script>

<template>
  <Head title="Settings - UmkmAI" />

  <div class="min-h-screen bg-gradient-to-br from-[#FFF5F5] via-[#F0FFF4] to-[#EFF6FF] p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold bg-gradient-to-r from-[#FF6B6B] to-[#4ECDC4] bg-clip-text text-transparent">
        Pengaturan ⚙️
      </h1>
      <p class="text-gray-600 mt-1">Konfigurasi AI, channel, dan token</p>
    </div>

    <!-- Loading State -->
    <div
      v-if="loading"
      class="flex items-center justify-center py-12"
    >
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#FF6B6B]"></div>
    </div>

    <!-- Settings Form -->
    <div v-else class="max-w-4xl">
      <!-- Tabs -->
      <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-2 mb-6 border-2 border-[#FFE8D6] shadow-sm">
        <div class="flex gap-2">
          <button
            v-for="tab in ['ai_prompt', 'channel', 'general']"
            :key="tab"
            @click="tabs = tab as any"
            :class="[
              'px-4 py-2 rounded-xl text-sm font-medium transition-all flex-1',
              tabs === tab
                ? 'bg-gradient-to-r from-[#FF6B6B] to-[#FF8E8E] text-white shadow-md'
                : 'text-gray-600 hover:bg-gray-100',
            ]"
          >
            {{ tab === 'ai_prompt' ? '🤖 AI' : tab === 'channel' ? '📱 Channel' : '🔧 Umum' }}
          </button>
        </div>
      </div>

      <!-- Alert Message -->
      <div
        v-if="testResult"
        :class="[
          'mb-6 px-4 py-3 rounded-2xl border-2 transition-all',
          testResult.success
            ? 'bg-green-100 border-green-200 text-green-800'
            : 'bg-red-100 border-red-200 text-red-800',
        ]"
      >
        {{ testResult.message }}
      </div>

      <form @submit.prevent="saveSettings" class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border-2 border-[#FFE8D6] shadow-sm">
        <!-- AI Prompt Settings -->
        <div v-if="tabs === 'ai_prompt'" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Nama Toko
            </label>
            <input
              v-model="settings.ai_prompt.business_name"
              type="text"
              placeholder="Contoh: Toko Sepatu Keren"
              class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Deskripsi Toko
            </label>
            <textarea
              v-model="settings.ai_prompt.business_description"
              rows="3"
              placeholder="Contoh: Toko sepatu online dengan kualitas terbaik dan harga terjangkau"
              class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all resize-none"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Daftar Produk
            </label>
            <textarea
              v-model="settings.ai_prompt.product_list"
              rows="5"
              placeholder="Contoh:
Sepatu Sneaker - Rp 250.000
Sepatu Formal - Rp 350.000
Sepatu Olahraga - Rp 300.000"
              class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all resize-none font-mono text-sm"
            ></textarea>
            <p class="text-xs text-gray-500 mt-1">Format: Nama Produk - Harga (satu produk per baris)</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Custom AI Prompt (Opsional)
            </label>
            <textarea
              v-model="settings.ai_prompt.custom_prompt"
              rows="4"
              placeholder="Tambahkan instruksi kustom untuk AI..."
              class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all resize-none"
            ></textarea>
            <p class="text-xs text-gray-500 mt-1">Ini akan ditambahkan ke system prompt default AI</p>
          </div>
        </div>

        <!-- Channel Settings -->
        <div v-if="tabs === 'channel'" class="space-y-6">
          <!-- WhatsApp Settings -->
          <div class="space-y-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-bold text-gray-900">📱 WhatsApp</h3>
              <button
                type="button"
                @click="testConnection('whatsapp')"
                :disabled="testing === 'whatsapp'"
                class="px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white font-semibold shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <span v-if="testing !== 'whatsapp'">Test Koneksi</span>
                <span v-else class="flex items-center gap-2">
                  <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Testing...
                </span>
              </button>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Phone Number ID
              </label>
              <input
                v-model="settings.channel.whatsapp_phone_number_id"
                type="text"
                placeholder="Contoh: 123456789012345"
                class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Access Token
              </label>
              <input
                v-model="settings.channel.whatsapp_access_token"
                type="password"
                placeholder="WhatsApp Access Token"
                class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Verify Token
              </label>
              <input
                v-model="settings.channel.whatsapp_verify_token"
                type="text"
                placeholder="Random verify token"
                class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                App Secret
              </label>
              <input
                v-model="settings.channel.whatsapp_app_secret"
                type="password"
                placeholder="WhatsApp App Secret"
                class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
              />
            </div>
          </div>

          <!-- Instagram Settings -->
          <div class="space-y-4 pt-6 border-t-2 border-[#FFE8D6]">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-bold text-gray-900">📸 Instagram</h3>
              <button
                type="button"
                @click="testConnection('instagram')"
                :disabled="testing === 'instagram'"
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <span v-if="testing !== 'instagram'">Test Koneksi</span>
                <span v-else class="flex items-center gap-2">
                  <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Testing...
                </span>
              </button>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Access Token
              </label>
              <input
                v-model="settings.channel.instagram_access_token"
                type="password"
                placeholder="Instagram Access Token"
                class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Verify Token
              </label>
              <input
                v-model="settings.channel.instagram_verify_token"
                type="text"
                placeholder="Random verify token"
                class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                App Secret
              </label>
              <input
                v-model="settings.channel.instagram_app_secret"
                type="password"
                placeholder="Instagram App Secret"
                class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
              />
            </div>
          </div>
        </div>

        <!-- General Settings -->
        <div v-if="tabs === 'general'" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Anthropic API Key
            </label>
            <input
              v-model="settings.general.anthropic_api_key"
              type="password"
              placeholder="sk-ant-xxxxxxxxxxxxxxxxxxxxxxxx"
              class="w-full px-4 py-3 rounded-xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all font-mono"
            />
            <p class="text-xs text-gray-500 mt-1">API key untuk Claude AI (Anthropic)</p>
          </div>
        </div>

        <!-- Save Button -->
        <div class="flex gap-3 mt-8 pt-6 border-t-2 border-[#FFE8D6]">
          <button
            type="submit"
            :disabled="saving"
            class="flex-1 px-6 py-3 rounded-xl bg-gradient-to-r from-[#FF6B6B] to-[#FF8E8E] text-white font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex items-center justify-center gap-2"
          >
            <span v-if="!saving">Simpan Pengaturan</span>
            <span v-else class="flex items-center gap-2">
              <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Menyimpan...
            </span>
          </button>

          <button
            type="button"
            @click="router.visit(route('inbox.index'))"
            class="px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition-all"
          >
            Kembali
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
