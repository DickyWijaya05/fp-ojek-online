import { Injectable } from '@angular/core';
import { initializeApp, getApps } from 'firebase/app';
import {
  getAuth,
  signInWithPopup,
  GoogleAuthProvider,
  signOut,
  onAuthStateChanged,
  User
} from 'firebase/auth';
import { BehaviorSubject } from 'rxjs';
import { firebaseConfig } from './firebase.config';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment'; // ✅ Tambahkan ini

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  user = new BehaviorSubject<any | null>(null);
  private auth;

  constructor(private http: HttpClient, private router: Router) {
    if (!getApps().length) {
      initializeApp(firebaseConfig);
    }

    this.auth = getAuth();

    onAuthStateChanged(this.auth, (currentUser) => {
      if (currentUser) {
        this.user.next(currentUser);
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
   * Login dengan Google
   */
  async loginWithGoogle(): Promise<User | null> {
    const provider = new GoogleAuthProvider();
    try {
      const result = await signInWithPopup(this.auth, provider);
      if (!result.user.uid) throw new Error('UID Firebase tidak ditemukan!');
      this.user.next(result.user);
      return result.user;
    } catch (error) {
      console.error('Google login error', error);
      throw error;
    }
  }

  /**
   * Kirim data user dari Google ke backend Laravel
   */
  sendUserDataToLaravel(user: any, levelId: number = 3) {
    const payload = {
      uid: user.uid,
      name: user.displayName || user.name || localStorage.getItem('name') || 'Pengguna',
      email: user.email || '',
      phone: user.phoneNumber || '',
      photo_url: user.photoURL || '',
      level_id: levelId
    };

    return this.http.post<{ user: any; token: string }>(
      `${environment.apiUrl}/store-user`, // ✅ pakai env
      payload
    );
  }

  /**
   * Ambil data user dari backend berdasarkan email
   */
  getUserFromLaravel(email: string) {
    return this.http.post<any>(
      `${environment.apiUrl}/get-user`, // ✅ pakai env
      { email }
    );
  }

  /**
   * Login manual dengan email dan password
   */
  loginWithEmail(email: string, password: string) {
    const data = { email, password };
    return this.http.post<{ user: any; token: string }>(
      `${environment.apiUrl}/login`, // ✅ pakai env
      data
    );
  }

  /**
   * Registrasi manual customer
   */
  registerUser(data: any) {
    return this.http.post<{ user: any; token: string }>(
      `${environment.apiUrl}/register`, // ✅ pakai env
      data
    );
  }

  /**
   * Registrasi driver
   */
  registerDriver(data: any) {
    return this.http.post<{ user: any; token: string }>(
      `${environment.apiUrl}/register-driver`, // ✅ pakai env
      data
    );
  }

  /**
   * Logout
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
}
