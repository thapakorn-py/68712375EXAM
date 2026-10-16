import { createRouter, createWebHistory } from 'vue-router'
import studentList from '../views/studentList.vue'
import studentAdd from '../views/studentAdd.vue'
import studentEdit from '../views/studentEdit.vue'

const routes = [
  { path: '/', name: 'list', component: studentList },
  { path: '/add', name: 'add', component: studentAdd },
  { path: '/edit/:id', name: 'edit', component: studentEdit }
]

export default createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})
