import { Component } from '@angular/core';
import * as L from 'leaflet';
import { Geolocation } from '@capacitor/geolocation';
import axios from 'axios';

@Component({
  standalone: false,
  selector: 'app-driver-tracking',
  templateUrl: './driver-tracking.page.html',
  styleUrls: ['./driver-tracking.page.scss'],
})
export class DriverTrackingPage {
  map: L.Map | undefined;
  driverMarker: L.Marker | undefined;
  currentLat: number = 0;
  currentLng: number = 0;

  async ionViewDidEnter() {
    const position = await Geolocation.getCurrentPosition({
      enableHighAccuracy: true,
    });

    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    this.currentLat = lat;
    this.currentLng = lng;

    this.map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(this.map);

    const driverIcon = L.icon({
      iconUrl: 'assets/alert-icon.svg',
      iconSize: [32, 32],
      iconAnchor: [16, 32],
      popupAnchor: [0, -32],
    });

    this.driverMarker = L.marker([lat, lng], {
      icon: driverIcon,
      draggable: true
    })
    .addTo(this.map)
    .bindPopup(`📍 Lat: ${lat.toFixed(5)}<br>Lng: ${lng.toFixed(5)}`)
    .openPopup()
    .on('dragend', (e: any) => {
      const newLatLng = e.target.getLatLng();
      this.currentLat = newLatLng.lat;
      this.currentLng = newLatLng.lng;

      this.driverMarker?.setPopupContent(
        `📍 Lat: ${newLatLng.lat.toFixed(5)}<br>Lng: ${newLatLng.lng.toFixed(5)}`
      ).openPopup();

      this.map?.setView(newLatLng);
    });

    setTimeout(() => {
      this.map?.invalidateSize();
    }, 100);
  }

  async simpanLokasi() {
    const token = localStorage.getItem('token');
    if (!token) {
      alert('Token tidak ditemukan. Silakan login ulang.');
      return;
    }

    try {
      const response = await axios.post(
        'http://localhost:8000/api/driver-location',
        {
          latitude: this.currentLat,
          longitude: this.currentLng
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      alert('✅ Lokasi driver berhasil disimpan!');
      console.log('Respon:', response.data);
    } catch (error) {
      console.error('❌ Gagal simpan lokasi:', error);
      alert('Gagal menyimpan lokasi driver ke server.');
    }
  }
}