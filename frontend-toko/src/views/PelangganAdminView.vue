<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '../components/AdminLayout.vue'
import api from '../utils/api'

const pelanggans = ref([])
const loading = ref(true)

// State Form
const showForm = ref(false)
const isEdit = ref(false)
const form = ref({
  id_pelanggan: null,
  nama_pelanggan: '',
  alamat: ''
})

// Ambil daftar pelanggan
const fetchPelanggan = async () => {
  loading.value = true
  try {
    const response = await api.get('/pelanggan')
    pelanggans.value = response.data.data || response.data
  } catch (error) {
    console.error("Gagal mengambil pelanggan", error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchPelanggan()
})

// Buka form Tambah
const openAddForm = () => {
  isEdit.value = false
  form.value = { id_pelanggan: null, nama_pelanggan: '', alamat: '' }
  showForm.value = true
}

// Buka form Edit
const openEditForm = (pelanggan) => {
  isEdit.value = true
  form.value = { 
    id_pelanggan: pelanggan.id_pelanggan || pelanggan.id,
    nama_pelanggan: pelanggan.nama_pelanggan || pelanggan.nama || '',
    alamat: pelanggan.alamat || ''
  }
  showForm.value = true
}

// Simpan Data (Tambah & Edit)
const savePelanggan = async () => {
  try {
    const payload = {
      nama_pelanggan: form.value.nama_pelanggan,
      alamat: form.value.alamat
    }

    if (isEdit.value) {
      await api.put(`/pelanggan/${form.value.id_pelanggan}`, payload)
    } else {
      await api.post('/pelanggan', payload)
    }
    showForm.value = false
    fetchPelanggan()
  } catch (error) {
    alert("Gagal menyimpan data: " + (error.response?.data?.message || error.message))
  }
}

// Hapus Data
const hapusPelanggan = async (id) => {
  if (confirm('Yakin ingin menghapus data pelanggan ini?')) {
    try {
      await api.delete(`/pelanggan/${id}`)
      fetchPelanggan()
    } catch (error) {
      alert("Gagal menghapus pelanggan")
    }
  }
}
</script>

<template>
  <AdminLayout>
    <div class="fade-in">
      <div class="d-flex justify-between align-center" style="margin-bottom: 1.5rem;">
        <h2>Manajemen Pelanggan</h2>
        <button v-if="!showForm" @click="openAddForm" class="btn btn-primary">+ Tambah Pelanggan</button>
      </div>

      <!-- Form Tambah / Edit Pelanggan -->
      <div v-if="showForm" class="glass-card mb-4" style="margin-bottom: 2rem;">
        <h3>{{ isEdit ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' }}</h3>
        <form @submit.prevent="savePelanggan" style="margin-top: 1.5rem;">
          <div class="grid grid-cols-2">
            <div class="form-group">
              <label class="form-label">Nama Pelanggan</label>
              <input type="text" v-model="form.nama_pelanggan" class="form-control" required placeholder="Masukkan nama pelanggan">
            </div>

            <div class="form-group">
              <label class="form-label">Alamat</label>
              <input type="text" v-model="form.alamat" class="form-control" required placeholder="Masukkan alamat">
            </div>
          </div>

          <div class="d-flex gap-2" style="justify-content: flex-end; margin-top: 1.5rem;">
            <button type="button" @click="showForm = false" class="btn btn-secondary">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>

      <!-- Tabel Data Pelanggan -->
      <div class="glass-card table-responsive">
        <div v-if="loading" class="text-center text-muted" style="padding: 2rem;">Memuat data...</div>
        <table v-else>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Pelanggan</th>
              <th>Alamat</th>
              <th style="text-align: right;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="pelanggan in pelanggans" :key="pelanggan.id_pelanggan || pelanggan.id">
              <td>{{ pelanggan.id_pelanggan || pelanggan.id }}</td>
              <td><strong>{{ pelanggan.nama_pelanggan || pelanggan.nama || '-' }}</strong></td>
              <td>{{ pelanggan.alamat || '-' }}</td>
              <td style="text-align: right;">
                <div class="d-flex gap-2" style="justify-content: flex-end;">
                  <button @click="openEditForm(pelanggan)" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Edit</button>
                  <button @click="hapusPelanggan(pelanggan.id_pelanggan || pelanggan.id)" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Hapus</button>
                </div>
              </td>
            </tr>
            <tr v-if="pelanggans.length === 0">
              <td colspan="4" class="text-center text-muted" style="padding: 2rem;">Belum ada data pelanggan.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>