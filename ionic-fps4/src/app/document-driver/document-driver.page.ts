import { Component } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';
import { AlertController } from '@ionic/angular';

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

  constructor(
    private http: HttpClient,
    private router: Router,
    private alertController: AlertController
  ) {}

  onFileChange(event: any, docType: string) {
    const file = event.target.files[0];
    if (file) {
      this.documents[docType] = file;
    }
  }

  async submitDocuments() {
    // Validasi dokumen tidak boleh kosong
    const isAnyFileMissing = Object.values(this.documents).some(file => file === null);
    if (!this.vehicleName.trim() || !this.vehicleType.trim() || isAnyFileMissing) {
      this.showAlert('Semua data kendaraan dan dokumen wajib diisi!');
      return;
    }

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
        async (response) => {
          console.log('✅ Dokumen berhasil dikirim:', response);
          await this.showAlert('Dokumen berhasil dikirim!', 'Sukses');
          this.router.navigate(['/login']);
        },
        async (error) => {
          console.error('❌ Gagal mengirim dokumen:', error);
          await this.showAlert('Gagal mengirim dokumen. Silakan coba lagi.');
        }
      );
  }

  async showAlert(message: string, header: string = 'Peringatan') {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: [
        {
          text: 'Oke',
          role: 'cancel',
          cssClass: 'elegant-alert-button',
        },
      ],
      cssClass: 'elegant-alert',
      backdropDismiss: false,
    });
    await alert.present();
  }
}
