# Exam CRUD — Vue 3 + Bootstrap (npm) + PHP + MySQL

โปรเจกต์ฝึกสอน: เพิ่ม / แสดง / ลบ / แก้ไข ข้อมูลในฐานข้อมูล MySQL ด้วย Vue.js

## วิธีรันโปรเจกต์นี้
1. เปิด XAMPP และ Start **MySQL**
2. เปิด phpMyAdmin → สร้างฐานข้อมูล `student_db` → แท็บ **Import** → เลือก `api/students.sql` → Go
3. เปิด Terminal ในโฟลเดอร์โปรเจกต์ แล้วเริ่ม PHP API:
   ```powershell
   C:\xampp\php\php.exe -S localhost:8001 -t .
   ```
4. เปิด Terminal อีกหน้าต่าง แล้วรัน Vue:
   ```
   npm install
   npm run serve
   ```
5. เปิด `http://localhost:8080`

Vue development server forwards `/api` requests to `http://localhost:8001`, so keep the PHP API server running while using the app. If MySQL is not on the default port or credentials differ, update the connection settings in `api/condb.php`.

---

## ขั้นตอนทำข้อสอบตั้งแต่ศูนย์ (สร้าง Project ใหม่ + ติดตั้ง Bootstrap)

### 1) สร้างโปรเจกต์ Vue
```
vue create exam_crud
```
เลือก **Manually select features** → ติ๊ก **Babel** และ **Router** → เลือก **Vue 3** → History mode = Yes

(ถ้าไม่มีคำสั่ง vue: `npm install -g @vue/cli`)

### 2) ติดตั้ง Bootstrap
```
cd exam_crud
npm install bootstrap
```
แล้วเพิ่ม 2 บรรทัดนี้ใน `src/main.js`
```js
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
```

### 3) สร้างฐานข้อมูล
phpMyAdmin → สร้าง database → สร้างตาราง (มี `AUTO_INCREMENT` ที่ primary key) → ดูตัวอย่างที่ `api/db_exam.sql`

### 4) เขียน PHP API ไว้ใน `htdocs/<ชื่อโปรเจกต์>/api/`
| ไฟล์ | หน้าที่ | วิธีส่ง |
|---|---|---|
| `condb.php` | เชื่อมต่อ DB + เปิด CORS | — |
| `show_customer.php` | แสดงทั้งหมด / `?id=` แสดงคนเดียว | GET |
| `add_customer.php` | เพิ่ม | POST (JSON) |
| `update_customer.php` | แก้ไข | POST (JSON) |
| `delete_customer.php` | ลบ | POST (JSON) |

### 5) เขียนหน้า Vue
| ไฟล์ | หน้าที่ | Route |
|---|---|---|
| `views/CustomerList.vue` | แสดง + ปุ่มลบ | `/` |
| `views/CustomerAdd.vue` | ฟอร์มเพิ่ม | `/add` |
| `views/CustomerEdit.vue` | ฟอร์มแก้ไข | `/edit/:id` |

อย่าลืมเพิ่ม route ใน `src/router/index.js`

---

## จุดที่ต้องเปลี่ยนเมื่อโจทย์ใช้ตารางอื่น (เช่น products, students)
1. `api/condb.php` → `$dbname` (ตั้งเป็น `student_db` สำหรับ `api/students.sql`)
2. `api/*.php` → ชื่อตาราง, ชื่อคอลัมน์ และ primary key
3. `src/views/*.vue` → ช่อง input (`v-model`) และคอลัมน์ในตาราง
4. `src/config.js` → `API_BASE` ให้ตรงกับที่อยู่ของ API

## Checklist ก่อนส่ง
- [ ] Apache + MySQL เปิดอยู่
- [ ] `npm run serve` ไม่มี error
- [ ] เพิ่มข้อมูลได้ และเห็นในตารางทันที
- [ ] แก้ไขแล้วข้อมูลเปลี่ยนจริง
- [ ] ลบแล้วหายจริง (เช็คใน phpMyAdmin ด้วย)
