import { Component, OnInit } from '@angular/core';
import { AlertController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-form-register',
  templateUrl: './form-register.page.html',
  styleUrls: ['./form-register.page.scss'],
})
export class FormRegisterPage implements OnInit {
  fullName: string = '';
  email: string = '';
  phone: string = '';
  password: string = '';
  confirmPassword: string = '';

  showPassword: boolean = false;
  showConfirmPassword: boolean = false;

  constructor(
    private alertController: AlertController,
    private authService: AuthService,
    private router: Router
  ) { }

  ngOnInit() { }

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }

  toggleConfirmPasswordVisibility() {
    this.showConfirmPassword = !this.showConfirmPassword;
  }

  async registerWithGoogle() {
    try {
      const user = await this.authService.loginWithGoogle();
this.authService.sendUserDataToLaravel(user, 3); // penumpang

      console.log('Registrasi Google berhasil:', user);
      if (user) {
        this.router.navigateByUrl('/login-costumer');
      }
    } catch (error) {
      console.error('Registrasi Google gagal:', error);
      this.showAlert('Gagal daftar dengan Google, coba lagi.');
    }
  }

  async onContinue() {
    const passwordRegex = /^[A-Z][a-zA-Z0-9]*[0-9]+[a-zA-Z0-9]*$/;

    if (!this.fullName || !this.email || !this.phone || !this.password || !this.confirmPassword) {
      this.showAlert('Semua kolom wajib diisi!');
      return;
    }

    if (!passwordRegex.test(this.password)) {
      this.showAlert('Password harus diawali huruf besar dan mengandung angka.');
      return;
    }

    if (this.password !== this.confirmPassword) {
      this.showAlert('Konfirmasi password tidak cocok.');
      return;
    }

    const data = {
      name: this.fullName,
      email: this.email,
      phone: String(this.phone),
      password: this.password,
      password_confirmation: this.confirmPassword, // ✅ untuk validasi Laravel
      level_id: 3
    };

    this.authService.registerUser(data).subscribe({
      next: async (res) => {
        await this.showAlert('Registrasi berhasil!', 'Sukses');
        this.router.navigateByUrl('/login');
      },
      error: async (err) => {
        console.error('❌ Registrasi gagal:', err);
        this.showAlert('Registrasi gagal. Coba lagi.');
      }
    });
  }

  async showAlert(message: string, header: string = 'Peringatan') {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: ['OK']
    });
    await alert.present();
  }
}
