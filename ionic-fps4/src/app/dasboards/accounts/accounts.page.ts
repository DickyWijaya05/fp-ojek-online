import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  standalone: false,
  selector: 'app-accounts',
  templateUrl: './accounts.page.html',
  styleUrls: ['./accounts.page.scss'],
})
export class AccountsPage implements OnInit {
  @ViewChild('fileInput', { static: false }) fileInput!: ElementRef<HTMLInputElement>;

  profile: any = {
    name: '',
    email: '',
    phone: '',
    foto_profil: '',
    gender: '',
    date_of_birth: ''
  };

  isEditing = false;

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.loadProfile();
  }

  loadProfile() {
    const token = localStorage.getItem('driver_token');
    this.http.get('http://localhost:8000/api/driver/profile', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe({
      next: (res: any) => {
        this.profile = res;
      },
      error: (err) => {
        console.error('Gagal mengambil profil driver:', err);
      }
    });
  }

  saveProfile() {
    const token = localStorage.getItem('driver_token');
    const data = {
      name: this.profile.name,
      phone: this.profile.phone,
      jenis_kelamin: this.profile.gender === 'Male' ? 'L' : 'P',
      tanggal_lahir: this.profile.date_of_birth
    };

    this.http.put('http://localhost:8000/api/driver/profile', data, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe(() => {
      this.isEditing = false;
      this.loadProfile();
    });
  }

  uploadPhoto(event: any) {
    const token = localStorage.getItem('driver_token');
    const file = event.target.files[0];
    const formData = new FormData();
    formData.append('foto_profil', file);

    this.http.post('http://localhost:8000/api/driver/profile/upload-photo', formData, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe((res: any) => {
      this.profile.foto_profil = res.foto_profil;
    });
  }

  triggerFileInput() {
    this.fileInput.nativeElement.click();
  }
}
