import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-login-costumer',
  templateUrl: './login-costumer.page.html',
  styleUrls: ['./login-costumer.page.scss'],
})
export class LoginCostumerPage {
  email: string = '';
  password: string = '';
  showPassword: boolean = false;

  constructor(private authService: AuthService, private router: Router) {}

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }

  // ✅ Login dengan email dan password
  loginWithEmail() {
    this.authService.loginWithEmail(this.email, this.password).subscribe({
      next: (res) => {
        const user = res.user;
        const token = res.token;

        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('token', token);

        // Arahkan ke dashboard berdasarkan level_id
        if (user.level_id === 2) {
          this.router.navigateByUrl('/dasboards'); // untuk driver
        } else if (user.level_id === 3) {
          this.router.navigateByUrl('/dasboard'); // untuk penumpang
        } else {
          alert('Level pengguna tidak dikenali.');
        }
      },
      error: (err: any) => {
        console.error('Login gagal:', err);
        if (err.status === 403 && err.error?.message) {
          alert(err.error.message); // pesan dari Laravel
        } else if (err.status === 404) {
          alert('Email belum terdaftar.');
        } else {
          alert(
            'Login gagal. Email atau password salah, atau Anda login via Google. Silakan Login Via Google.'
          );
        }
      }
    });
  }

  // ✅ Login via Google
  async loginWithGoogle() {
    try {
      const firebaseUser = await this.authService.loginWithGoogle();
      if (firebaseUser && firebaseUser.email) {
        this.authService.sendUserDataToLaravel(firebaseUser, 3).subscribe({
          next: (res: any) => {
            const user = res.user;
            const token = res.token;

            localStorage.setItem('user', JSON.stringify(user));
            localStorage.setItem('token', token);

            if (user.level_id === 2) {
              this.router.navigateByUrl('/dasboards'); // driver
            } else if (user.level_id === 3) {
              this.router.navigateByUrl('/dasboard'); // customer
            } else {
              alert('Level pengguna tidak dikenali.');
            }
          },
          error: (err: any) => {
            console.error('❌ Gagal simpan user dari Google ke Laravel:', err);
            alert(err.error?.message || 'Login Google gagal. Silakan coba lagi.');
          }
        });
      } else {
        alert('Data user dari Firebase tidak lengkap.');
      }
    } catch (error) {
      console.error('Login Google gagal:', error);
      alert('Login Google gagal, coba lagi.');
    }
  }

  goToRegister() {
    this.router.navigateByUrl('/register-option');
  }
}
