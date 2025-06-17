import { Component, OnInit } from '@angular/core';
import { AlertController } from '@ionic/angular';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

@Component({
  standalone: false,
  selector: 'app-registers',
  templateUrl: './registers.page.html',
  styleUrls: ['./registers.page.scss'],
})
export class RegistersPage implements OnInit {

  fullName: string = '';
  email: string = '';
  phone: string = '';
  password: string = '';
  confirmPassword: string = '';

  showPassword: boolean = false;
  showConfirmPassword: boolean = false;

  constructor(
    private authService: AuthService,
    private alertController: AlertController,
    private router: Router
  ) { }

  ngOnInit() { }

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }

  toggleConfirmPasswordVisibility() {
    this.showConfirmPassword = !this.showConfirmPassword;
  }

  async onContinue() {
    const passwordRegex = /^[A-Z][a-zA-Z0-9]*[0-9]+[a-zA-Z0-9]*$/;

    if (
      !this.fullName.trim() ||
      !this.email.trim() ||
      !this.phone.trim() ||
      !this.password.trim() ||
      !this.confirmPassword.trim()
    ) {
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

    const driverData = {
      name: this.fullName,
      email: this.email,
      phone: this.phone,
      level_id: 2, // 2 = Driver
      password: this.password,
      password_confirmation: this.confirmPassword
    };

    try {
      const response = await this.authService.registerDriver(driverData).toPromise();
      
      if (response) {
  localStorage.setItem('auth_token', response.token);
  localStorage.setItem('user', JSON.stringify(response.user));
}

      this.showAlert('Registrasi berhasil!', 'Sukses');
      this.router.navigateByUrl('/document-driver'); // Arahkan ke upload dokumen
    } catch (error) {
      console.error('Registrasi gagal:', error);
      this.showAlert('Registrasi gagal. Coba lagi.');
    }
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
