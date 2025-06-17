import { Component } from '@angular/core';
import * as L from 'leaflet';
import { Geolocation } from '@capacitor/geolocation';
import axios from 'axios';

@Component({
  standalone: false,
  selector: 'app-user-tracking',
  templateUrl: './user-tracking.page.html',
  styleUrls: ['./user-tracking.page.scss'],
})
export class UserTrackingPage {
  map: L.Map | undefined;
  startMarker: L.Marker | undefined;
  destMarker: L.Marker | undefined;

  startQuery = '';
  destQuery = '';

  startCoords: { lat: number; lng: number } | null = null;
  destCoords: { lat: number; lng: number } | null = null;

  async ionViewDidEnter() {
    const position = await Geolocation.getCurrentPosition();
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    this.map = L.map('map').setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(this.map);

    // START marker
    this.startCoords = { lat, lng };
    this.startMarker = L.marker([lat, lng], {
      draggable: true,
      icon: L.icon({
        iconUrl: 'assets/Logo.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32]
      })
    })
      .addTo(this.map)
      .bindPopup(`📍 Lokasi Awal:<br>${lat.toFixed(6)}, ${lng.toFixed(6)}`)
      .openPopup();

    this.startMarker.on('dragend', (event: L.LeafletEvent) => {
      const pos = (event.target as L.Marker).getLatLng();
      this.startCoords = { lat: pos.lat, lng: pos.lng };
      this.startMarker?.setPopupContent(`📍 Lokasi Awal (Diseret):<br>${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`).openPopup();
    });

    // DEST marker
    this.destCoords = { lat: lat + 0.005, lng };
    this.destMarker = L.marker([lat + 0.005, lng], {
      draggable: true,
      icon: L.icon({
        iconUrl: 'assets/Logo.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32]
      })
    })
      .addTo(this.map)
      .bindPopup(`📍 Tujuan:<br>${(lat + 0.005).toFixed(6)}, ${lng.toFixed(6)}`);

    this.destMarker.on('dragend', (event: L.LeafletEvent) => {
      const pos = (event.target as L.Marker).getLatLng();
      this.destCoords = { lat: pos.lat, lng: pos.lng };
      this.destMarker?.setPopupContent(`📍 Tujuan (Diseret):<br>${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`).openPopup();
    });

    setTimeout(() => this.map?.invalidateSize(), 100);
  }

  async searchLocation(type: 'start' | 'dest') {
    const query = type === 'start' ? this.startQuery : this.destQuery;
    if (!query) return;

    try {
      const response = await axios.get('http://localhost:8000/api/search-location', {
        params: { q: query, country: 'ID' }
      });

      if (response.data && response.data.length > 0) {
        const { lat, lon, display_name } = response.data[0];
        const coords: [number, number] = [parseFloat(lat), parseFloat(lon)];

        if (type === 'start') {
          this.startCoords = { lat: coords[0], lng: coords[1] };
          this.startMarker?.setLatLng(coords).bindPopup(`📍 Lokasi Awal:<br>${display_name}`).openPopup();
        } else {
          this.destCoords = { lat: coords[0], lng: coords[1] };
          this.destMarker?.setLatLng(coords).bindPopup(`📍 Tujuan:<br>${display_name}`).openPopup();
        }

        this.map?.setView(coords, 15);
      } else {
        alert('Lokasi tidak ditemukan');
      }
    } catch (error) {
      console.error('Gagal mencari lokasi:', error);
      alert('Gagal mencari lokasi');
    }
  }

  saveLocations() {
    console.log('📦 Lokasi Awal:', this.startCoords);
    console.log('📦 Tujuan:', this.destCoords);
    alert(
      `✅ Lokasi disimpan:\n\nAwal: ${this.startCoords?.lat.toFixed(6)}, ${this.startCoords?.lng.toFixed(6)}\n` +
      `Tujuan: ${this.destCoords?.lat.toFixed(6)}, ${this.destCoords?.lng.toFixed(6)}`
    );
  }
}
