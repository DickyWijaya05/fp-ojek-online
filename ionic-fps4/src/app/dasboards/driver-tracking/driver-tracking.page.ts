import { Component } from '@angular/core';
import * as L from 'leaflet';
import { Geolocation } from '@capacitor/geolocation';

@Component({
  standalone: false,
  selector: 'app-driver-tracking',
  templateUrl: './driver-tracking.page.html',
  styleUrls: ['./driver-tracking.page.scss'],
})
export class DriverTrackingPage {
  map: L.Map | undefined;
  driverMarker: L.Marker | undefined;

  async ionViewDidEnter() {
    const position = await Geolocation.getCurrentPosition();
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    // Inisialisasi peta
    this.map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(this.map);

    // Marker khusus untuk driver
    const driverIcon = L.icon({
      iconUrl: 'assets/alert-icon.svg', // Ganti dengan path ikon driver jika ada
      iconSize: [32, 32],
      iconAnchor: [16, 32],
      popupAnchor: [0, -32],
    });

    this.driverMarker = L.marker([lat, lng], { icon: driverIcon })
      .addTo(this.map)
      .bindPopup('📍 Lokasi Driver')
      .openPopup();

    // Resize agar peta tampil dengan benar
    setTimeout(() => {
      this.map?.invalidateSize();
    }, 100);

    // Update lokasi driver setiap 5 detik
    setInterval(() => this.updateLocation(), 5000);
  }

  async updateLocation() {
    const position = await Geolocation.getCurrentPosition();
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    if (this.driverMarker) {
      this.driverMarker.setLatLng([lat, lng]);
      this.driverMarker.setPopupContent('📍 Lokasi Driver').openPopup();
    }

    if (this.map) {
      this.map.setView([lat, lng]);
    }
  }
}
