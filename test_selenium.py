import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support import expected_conditions as EC
import time

class TestGateControlAI(unittest.TestCase):

    def setUp(self):
        """Dijalankan sebelum setiap skenario test dimulai."""
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        # Sesuaikan base_url dengan URL lokal (Laravel) atau Vercel Anda
        self.base_url = "https://project-31vv3.vercel.app" 
        
        # Buka halaman utama (asumsi ini akan merender layout utama atau langsung ke /login)
        self.driver.get(self.base_url)

    def login_helper(self):
        """Fungsi pembantu untuk melakukan login cepat agar bisa mengakses halaman lain."""
        driver = self.driver
        driver.get(self.base_url + "/login")
        
        # Cari input username dan password
        username_field = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.ID, "username"))
        )
        password_field = driver.find_element(By.ID, "password")
        submit_btn = driver.find_element(By.ID, "submitBtn")
        
        # Isi kredensial
        username_field.send_keys("admin@sistem.com")
        password_field.send_keys("admin123") 
        submit_btn.click()
        
        # Tunggu sampai SweetAlert "Berhasil!" muncul[cite: 6]
        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.XPATH, "//div[contains(@class, 'swal2-success')]"))
        )
        
        # Tunggu sampai redirect ke /dashboard selesai[cite: 6]
        WebDriverWait(driver, 10).until(
            EC.url_contains("/dashboard")
        )

    def test_00_login_empty_fields(self):
        """Skenario 0: Menguji validasi form login kosong (harus memunculkan SweetAlert peringatan)[cite: 6]."""
        driver = self.driver
        driver.get(self.base_url + "/login")
        
        # Klik tombol submit tanpa mengisi apa-apa[cite: 6]
        submit_btn = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.ID, "submitBtn"))
        )
        submit_btn.click()
        
        # Memverifikasi kemunculan SweetAlert peringatan[cite: 6]
        swal_warning = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CLASS_NAME, "swal2-warning"))
        )
        self.assertIsNotNone(swal_warning, "SweetAlert peringatan tidak muncul saat form kosong!")
        
        # Memverifikasi teks pesan error[cite: 6]
        swal_text = driver.find_element(By.ID, "swal2-html-container").text
        self.assertEqual(swal_text, "Email dan password tidak boleh kosong!")
        print("\n[LULUS] Validasi form login kosong berhasil.")
        
    def test_00a_login_toggle_password(self):
        """Skenario 0a: Menguji fungsi show/hide password[cite: 6]."""
        driver = self.driver
        driver.get(self.base_url + "/login")
        
        password_field = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.ID, "password"))
        )
        
        # Verifikasi tipe awal adalah 'password'[cite: 6]
        self.assertEqual(password_field.get_attribute("type"), "password")
        
        # Klik tombol eye[cite: 6]
        eye_btn = driver.find_element(By.CSS_SELECTOR, "button.eye-btn")
        eye_btn.click()
        
        # Verifikasi tipe berubah menjadi 'text'[cite: 6]
        self.assertEqual(password_field.get_attribute("type"), "text")
        print("\n[LULUS] Fungsi Toggle Password berhasil.")

    def test_01_dashboard_stats(self):
        """Skenario 1: Memastikan halaman Dashboard memuat kartu statistik."""
        # Melakukan login terlebih dahulu karena token diperlukan[cite: 6]
        self.login_helper() 
        
        driver = self.driver
        driver.get(self.base_url + "/dashboard")
        
        # Menunggu elemen statistik ID 'stat-hari-ini' muncul
        stat_hari_ini = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.ID, "stat-hari-ini"))
        )
        stat_mobil = driver.find_element(By.ID, "stat-mobil")
        stat_truk = driver.find_element(By.ID, "stat-truk")
        
        self.assertIsNotNone(stat_hari_ini, "Statistik Hari Ini tidak ditemukan!")
        self.assertIsNotNone(stat_mobil, "Statistik Mobil tidak ditemukan!")
        self.assertIsNotNone(stat_truk, "Statistik Truk tidak ditemukan!")
        print(f"\n[LULUS] Halaman Dashboard berhasil dimuat dengan elemen statistik.")

    def test_02_jadwal_operasional(self):
        """Skenario 2: Menguji form Pengaturan Jadwal Operasional."""
        self.login_helper()
        driver = self.driver
        driver.get(self.base_url + "/jadwal")
        
        # Mencari input waktu mulai dan waktu selesai
        waktu_mulai = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.ID, "waktuMulai"))
        )
        waktu_selesai = driver.find_element(By.ID, "waktuSelesai")
        
        # Mengubah nilai waktu
        waktu_mulai.clear()
        waktu_mulai.send_keys("07:00")
        waktu_selesai.clear()
        waktu_selesai.send_keys("17:00")
        
        # Menguji klik tombol hari aktif (misal: tombol Senin)
        tombol_senin = driver.find_element(By.CSS_SELECTOR, "button[data-day='senin']")
        tombol_senin.click()
        
        # Klik tombol "Simpan Jadwal" berdasarkan fungsi onclick
        tombol_simpan = driver.find_element(By.XPATH, "//button[@onclick='simpanJadwal()']")
        tombol_simpan.click()
        
        time.sleep(2) # Jeda untuk melihat perubahan UI animasi
        print("\n[LULUS] Interaksi form Jadwal Operasional berhasil.")

    def test_03_laporan_kendaraan_filter(self):
        """Skenario 3: Menguji fungsi filter pada halaman Laporan Kendaraan."""
        self.login_helper()
        driver = self.driver
        driver.get(self.base_url + "/reports")
        
        # Menunggu elemen input tanggal
        start_date = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.ID, "startDate"))
        )
        end_date = driver.find_element(By.ID, "endDate")
        
        # Memilih jenis kendaraan dari dropdown
        dropdown_jenis = Select(driver.find_element(By.ID, "vehicleType"))
        dropdown_jenis.select_by_value("mobil")
        
        # Klik tombol Filter
        btn_filter = driver.find_element(By.ID, "btnFilter")
        btn_filter.click()
        
        time.sleep(2) # Menunggu proses simulasi fetch data
        
        # Memastikan elemen Ringkasan (summaryTotal) ada
        summary_total = driver.find_element(By.ID, "summaryTotal")
        self.assertIsNotNone(summary_total)
        print("\n[LULUS] Filter Laporan Kendaraan berhasil dieksekusi.")

    def test_04_sidebar_navigation(self):
        """Skenario 4: Menguji perpindahan halaman lewat Sidebar."""
        self.login_helper()
        driver = self.driver
        driver.get(self.base_url + "/dashboard")
        
        # Menunggu menu sidebar Live View
        nav_liveview = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.XPATH, "//a[contains(@href, '/liveview')]"))
        )
        nav_liveview.click()
        time.sleep(2)
        
        # Memastikan URL berpindah ke liveview dan elemen kamera CCTV ada
        self.assertTrue("liveview" in driver.current_url)
        kamera_bg = driver.find_element(By.CLASS_NAME, "camera-bg")
        self.assertIsNotNone(kamera_bg)
        print("\n[LULUS] Navigasi sidebar ke Live View berhasil.")

    def tearDown(self):
        """Dijalankan setelah setiap test selesai untuk menutup browser."""
        self.driver.quit()

if __name__ == "__main__":
    unittest.main(verbosity=2)