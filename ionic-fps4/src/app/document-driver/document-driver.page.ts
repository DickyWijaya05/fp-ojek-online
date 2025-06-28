import { Component } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-document-driver',
  templateUrl: './document-driver.page.html',
  styleUrls: ['./document-driver.page.scss'],
})
export class DocumentDriverPage {
  vehicleName: string = '';
  vehicleColor: string = '';
  plateNumber: string = '';
  documents: { [key: string]: File | null } = {
    ktp: null,
    selfie_ktp: null,
    sim: null,
    stnk: null,
    pas_photo: null,
    vehicle_photo: null
  };

  constructor(private http: HttpClient, private router: Router) { }

  onFileChange(event: any, docType: string) {
    const file = event.target.files[0];
    if (file) {
      this.documents[docType] = file;
    }
  } 

  submitDocuments() {
    const formData = new FormData();
    formData.append('vehicle_name', this.vehicleName);
    formData.append('vehicle_color', this.vehicleColor);
    formData.append('plate_number', this.plateNumber);


    Object.keys(this.documents).forEach((key) => {
      const file = this.documents[key];
      if (file) {
        formData.append(key, file);
      }
    });

    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`
    });

    this.http.post('http://localhost:8000/api/driver/documents', formData, { headers })
      .subscribe(
        (response) => {
          console.log('✅ Dokumen berhasil dikirim:', response);
          alert('Dokumen berhasil dikirim!');
          this.router.navigate(['/login']);
        },
        (error) => {
          console.error('❌ Gagal mengirim dokumen:', error);
          alert('Gagal mengirim dokumen');
        }
      );
  }
}
