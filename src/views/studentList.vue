<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">รายชื่อลูกค้า</h2>
      <button class="btn btn-primary" @click="openAddStudent">+ เพิ่มลูกค้า</button>
    </div>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-if="loading" class="text-center">กำลังโหลดข้อมูล...</div>

    <table v-else class="table table-bordered table-striped text-center align-middle">
      <thead class="table-dark">
        <tr>
          <th>ลำดับ</th>
          <th>รหัส</th>
          <th>ชื่อ</th>
          <th>นามสกุล</th>
          <th>เบอร์โทร</th>
          <th>อีเมล</th>
          <th>จัดการ</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in students" :key="item.student_id">
          <td>{{ index + 1 }}</td>
          <td>{{ item.student_id }}</td>
          <td>{{ item.firstName }}</td>
          <td>{{ item.lastName }}</td>
          <td>{{ item.phone }}</td>
          <td>{{ item.email }}</td>
          <td>
            <button class="btn btn-warning btn-sm me-1" @click="editStudent(item)">แก้ไข</button>
            <button class="btn btn-danger btn-sm" @click="removeStudent(item)">ลบ</button>
          </td>
        </tr>
        <tr v-if="students.length === 0">
          <td colspan="7" class="text-muted">ยังไม่มีข้อมูล</td>
        </tr>
      </tbody>
    </table>

    <div ref="editStudentModal" class="modal fade" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form @submit.prevent="saveStudent">
            <div class="modal-header">
              <h5 id="editStudentModalLabel" class="modal-title">แก้ไขข้อมูลนักเรียน</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>
            <div class="modal-body">
              <div v-if="modalError" class="alert alert-danger">{{ modalError }}</div>
              <div class="mb-3">
                <label class="form-label" for="editFirstName">ชื่อ</label>
                <input id="editFirstName" v-model="editForm.firstName" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label" for="editLastName">นามสกุล</label>
                <input id="editLastName" v-model="editForm.lastName" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label" for="editPhone">เบอร์โทร</label>
                <input id="editPhone" v-model="editForm.phone" class="form-control" />
              </div>
              <div>
                <label class="form-label" for="editEmail">อีเมล</label>
                <input id="editEmail" v-model="editForm.email" type="email" class="form-control" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
              <button type="submit" class="btn btn-primary" :disabled="saving">
                {{ saving ? 'กำลังบันทึก...' : 'บันทึก' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div ref="addStudentModal" class="modal fade" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form @submit.prevent="addStudent">
            <div class="modal-header">
              <h5 id="addStudentModalLabel" class="modal-title">เพิ่มข้อมูลนักเรียน</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>
            <div class="modal-body">
              <div v-if="addModalError" class="alert alert-danger">{{ addModalError }}</div>
              <div class="mb-3">
                <label class="form-label" for="addFirstName">ชื่อ</label>
                <input id="addFirstName" v-model="addForm.firstName" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label" for="addLastName">นามสกุล</label>
                <input id="addLastName" v-model="addForm.lastName" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label" for="addPhone">เบอร์โทร</label>
                <input id="addPhone" v-model="addForm.phone" class="form-control" />
              </div>
              <div>
                <label class="form-label" for="addEmail">อีเมล</label>
                <input id="addEmail" v-model="addForm.email" type="email" class="form-control" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
              <button type="submit" class="btn btn-primary" :disabled="adding">
                {{ adding ? 'กำลังบันทึก...' : 'บันทึก' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Modal from 'bootstrap/js/dist/modal'
import { API_BASE } from '../config'

export default {
  name: 'studentList',
  data() {
    return {
      students: [],
      loading: true,
      error: '',
      editForm: { student_id: null, firstName: '', lastName: '', phone: '', email: '' },
      modalError: '',
      saving: false,
      addForm: { firstName: '', lastName: '', phone: '', email: '' },
      addModalError: '',
      adding: false
    }
  },
  mounted() {
    this.loadData()
  },
  methods: {
    // แสดงข้อมูล (Read)
    async fetchJson(url, options = {}) {
      const res = await fetch(url, options)
      if (!res.ok) {
        const text = await res.text()
        let message = `${res.status} ${res.statusText}`
        if (text) {
          try {
            const body = JSON.parse(text)
            message = body.message || message
          } catch (err) {
            message = text
          }
        }
        throw new Error(message)
      }
      return res.json()
    },

    async loadData() {
      this.loading = true
      try {
        const result = await this.fetchJson(`${API_BASE}/show_student.php`)
        if (!result.success) throw new Error(result.message || 'ไม่สามารถดึงข้อมูลได้')
        this.students = result.data
      } catch (err) {
        this.error = 'เกิดข้อผิดพลาด: ' + err.message
      } finally {
        this.loading = false
      }
    },

    editStudent(item) {
      this.editForm = { ...item }
      this.modalError = ''
      Modal.getOrCreateInstance(this.$refs.editStudentModal).show()
    },
    openAddStudent() {
      this.addForm = { firstName: '', lastName: '', phone: '', email: '' }
      this.addModalError = ''
      Modal.getOrCreateInstance(this.$refs.addStudentModal).show()
    },
    async addStudent() {
      this.adding = true
      this.addModalError = ''
      try {
        const result = await this.fetchJson(`${API_BASE}/add_student.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.addForm)
        })
        if (!result.success) throw new Error(result.message || 'ไม่สามารถบันทึกได้')
        Modal.getInstance(this.$refs.addStudentModal).hide()
        await this.loadData()
      } catch (err) {
        this.addModalError = 'เกิดข้อผิดพลาด: ' + err.message
      } finally {
        this.adding = false
      }
    },

    async saveStudent() {
      this.saving = true
      this.modalError = ''
      try {
        const result = await this.fetchJson(`${API_BASE}/update_student.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.editForm)
        })
        if (!result.success) throw new Error(result.message || 'ไม่สามารถบันทึกได้')
        this.students = this.students.map(student =>
          student.student_id === this.editForm.student_id ? { ...this.editForm } : student
        )
        Modal.getInstance(this.$refs.editStudentModal).hide()
      } catch (err) {
        this.modalError = 'เกิดข้อผิดพลาด: ' + err.message
      } finally {
        this.saving = false
      }
    },

    // ลบข้อมูล (Delete)
    async removeStudent(item) {
      if (!confirm(`ต้องการลบ ${item.firstName} ${item.lastName} ใช่หรือไม่?`)) return
      try {
        const result = await this.fetchJson(`${API_BASE}/delete_student.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ student_id: item.student_id })
        })
        if (!result.success) throw new Error(result.message || 'ไม่สามารถลบได้')
        this.students = this.students.filter(student => student.student_id !== item.student_id)
      } catch (err) {
        this.error = 'เกิดข้อผิดพลาด: ' + err.message
      }
    }
  }
}
</script>
