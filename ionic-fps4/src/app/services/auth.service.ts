import { Injectable } from '@angular/core';
import { initializeApp, getApps } from 'firebase/app';
import {
  getAuth,
  signInWithPopup,
  GoogleAuthProvider,
  signInWithPhoneNumber,
  ConfirmationResult,
  RecaptchaVerifier,
  User,
  signOut,
  onAuthStateChanged
} from 'firebase/auth';
import { BehaviorSubject } from 'rxjs';
import { firebaseConfig } from './firebase.config';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  user = new BehaviorSubject<any | null>(null);
  private confirmationResult?: ConfirmationResult;
  recaptchaVerifier?: RecaptchaVerifier;
  private recaptchaInitialized = false;
  private auth;

  constructor(private http: HttpClient, private router: Router) {
    if (!getApps().length) {
      initializeApp(firebaseConfig);
    }

    this.auth = getAuth();

    // Firebase auth state listener
    onAuthStateChanged(this.auth, (currentUser) => {
      if (currentUser) {
        // Default kirim ke Laravel tanpa level_id jika login ulang
        this.sendUserDataToLaravel(currentUser);
      } else {
        this.user.next(null);
        localStorage.removeItem('user');
        localStorage.removeItem('token');
      }
    });

    const localUser = localStorage.getItem('user');
    if (localUser) {
      this.user.next(JSON.parse(localUser));
    }
  }

  /**
   * Login dengan Google dan kirim data ke Laravel
   * @param levelId (2 = driver, 3 = penumpang)
   */
  async loginWithGoogle(levelId: number = 3): Promise<User | null> {
    const provider = new GoogleAuthProvider();
    try {
      const result = await signInWithPopup(this.auth, provider);

      if (!result.user.uid) throw new Error('UID Firebase tidak ditemukan!');

      this.user.next(result.user);
      this.sendUserDataToLaravel(result.user, levelId);
      return result.user;
    } catch (error) {
      console.error('Google login error', error);
      throw error;
    }
  }

  /**
   * Kirim data user dari Google atau registrasi ke Laravel
   * @param user dari Firebase
   * @param levelId default 3 (penumpang)
   */
  sendUserDataToLaravel(user: any, levelId: number = 3) {
    const payload = {
      uid: user.uid,
      name: user.displayName || '',
      email: user.email || '',
      phone: user.phoneNumber || '',
      photo_url: user.photoURL || '',
      level_id: levelId
    };

    this.http.post<{ user: any; token: string }>('http://localhost:8000/api/store-user', payload).subscribe({
      next: (res) => {
        console.log('Payload yang dikirim:', payload);

        console.log('✅ User data sent to Laravel:', res);
        localStorage.setItem('user', JSON.stringify(res.user));
        localStorage.setItem('token', res.token);
        this.user.next(res.user);
        
      },
      error: (error) => {
      console.error('❌ Registrasi gagal:', error);
      if (error.status === 422) {
        const errors = error.error.errors;
        console.warn('🟡 Detail validasi gagal:', errors);}
  }});
  }

  loginWithEmail(email: string, password: string) {
  const data = { email, password };
  return this.http.post<{ user: any; token: string }>('http://localhost:8000/api/login', data);
}


  /**
   * Register email/password manual costumer (dari form-register atau registers)
   */
  registerUser(data: any) {
    return this.http.post<{ user: any; token: string }>('http://localhost:8000/api/register', data);
  }

  /**
   * Register email/password manual driver (dari form-register atau registers)
   */
  registerDriver(data: any) {
  return this.http.post<{ user: any; token: string }>('http://localhost:8000/api/register-driver', data);
}

  /**
   * Logout dari Firebase & hapus local storage
   */
  async logout(): Promise<void> {
    try {
      await signOut(this.auth);
      this.user.next(null);
      localStorage.removeItem('user');
      localStorage.removeItem('token');
    } catch (error) {
      console.error('Logout error:', error);
      throw error;
    }
  }

  // Jika nanti ingin OTP login, bisa aktifkan ini
  // setupRecaptcha(containerId: string) {
  //   if (this.recaptchaInitialized) return;
  //   this.recaptchaVerifier = new RecaptchaVerifier(this.auth, containerId, {
  //     size: 'invisible',
  //     callback: (response: any) => console.log('reCAPTCHA solved:', response),
  //     'expired-callback': () => console.warn('reCAPTCHA expired.')
  //   });
  //   this.recaptchaVerifier.render();
  //   this.recaptchaInitialized = true;
  // }

  // async sendOTP(phoneNumber: string): Promise<void> {
  //   if (!this.recaptchaVerifier) throw new Error('reCAPTCHA belum diinisialisasi');
  //   this.confirmationResult = await signInWithPhoneNumber(this.auth, phoneNumber, this.recaptchaVerifier);
  //   console.log('OTP sent');
  // }

  // async verifyOTP(otpCode: string): Promise<User | null> {
  //   if (!this.confirmationResult) throw new Error('OTP belum dikirim.');
  //   const result = await this.confirmationResult.confirm(otpCode);
  //   this.user.next(result.user);
  //   this.sendUserDataToLaravel(result.user); // Tanpa level_id
  //   return result.user;
  // }
}
