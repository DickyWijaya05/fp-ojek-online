import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage {
  email: string = '';
  password: string = '';
  showPassword: boolean = false;

  constructor(private authService: AuthService, private router: Router) { }

  togglePasswordVisibility() {  // ✅ Fungsi untuk toggle ikon mata
    this.showPassword = !this.showPassword;
  }

  //login dengan email
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
      error: (err) => {
        console.error('Login gagal:', err);
        if (err.status === 403 && err.error?.message) {
          alert(err.error.message); // tampilkan pesan dari Laravel
        } else if (err.status === 404) {
          alert('Email belum terdaftar.');
        } else {
          alert('Login gagal. Email atau password salah.');
        }
      }
    });
  }


//   async loginWithGoogle() {
//     try {
//       const user = await this.authService.loginWithGoogle();
//       console.log('Login Google berhasil:', user);
//       if (user) {
//         this.router.navigateByUrl('/dasboard');
//       }
//     } catch (error) {
//       console.error('Login Google gagal:', error);
//       alert('Login Google gagal, coba lagi.');
//     }
//   }
 

//   goToRegister() {
//     this.router.navigateByUrl('/register-option');
//   }
}
