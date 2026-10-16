<template>
  <div class="container mt-4 col-md-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="text-center mb-3">เพิ่มลูกค้า</h2>
        <form @submit.prevent="addData">
          <div class="mb-2">
            <input v-model="form.firstName" class="form-control" placeholder="ชื่อ" required />
          </div>
          <div class="mb-2">
            <input v-model="form.lastName" class="form-control" placeholder="นามสกุล" required />
          </div>
          <div class="mb-2">
            <input v-model="form.phone" class="form-control" placeholder="เบอร์โทร" required />
          </div>
          <div class="mb-2">
            <input type="email" v-model="form.email" class="form-control" placeholder="อีเมล" />
          </div>
          <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <router-link to="/" class="btn btn-secondary ms-2">ยกเลิก</router-link>
          </div>
        </form>
        <div v-if="message" class="alert alert-info mt-3">{{ message }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import { API_BASE } from '../config'

export default {
  name: 'studentAdd',
  data() {
    return {
      form: { firstName: '', lastName: '', phone: '', email: '' },
      message: ''
    }
  },
  methods: {
    // เพิ่มข้อมูล (Create)
    async addData() {
      try {
        const res = await fetch(`${API_BASE}/add_student.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form)
        })
        const result = await res.json()
        this.message = result.message
        if (result.success) this.$router.push('/')
      } catch (err) {
        this.message = 'เกิดข้อผิดพลาด: ' + err.message
      }
    }
  }
}
</script>
