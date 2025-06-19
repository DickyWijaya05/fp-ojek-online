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

    this.startCoords = { lat, lng };
    this.startMarker = this.createMarker(lat, lng, '📍 Lokasi Awal');

    this.destCoords = { lat: lat + 0.005, lng };
    this.destMarker = this.createMarker(lat + 0.005, lng, '📍 Tujuan');

    Geolocation.watchPosition({}, (position, err) => {
      if (err) {
        console.error('❌ Gagal melacak lokasi:', err);
        return;
      }

      if (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        this.startCoords = { lat, lng };
        if (this.startMarker) {
          this.startMarker.setLatLng([lat, lng]);
          this.startMarker.setPopupContent(`📍 Lokasi Saat Ini:<br>${lat.toFixed(6)}, ${lng.toFixed(6)}`);
        }
        this.map?.panTo([lat, lng]);
      }
    });

    setTimeout(() => this.map?.invalidateSize(), 100);
  }

  createMarker(lat: number, lng: number, label: string): L.Marker {
    const marker = L.marker([lat, lng], {
      draggable: true,
      icon: L.icon({
        iconUrl: 'assets/Logo.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32]
      })
    }).addTo(this.map!)
      .bindPopup(`${label}:<br>${lat.toFixed(6)}, ${lng.toFixed(6)}`)
      .openPopup();

    marker.on('dragend', (event: L.LeafletEvent) => {
      const pos = (event.target as L.Marker).getLatLng();
      if (label.includes('Awal')) {
        this.startCoords = { lat: pos.lat, lng: pos.lng };
      } else {
        this.destCoords = { lat: pos.lat, lng: pos.lng };
      }
      marker.setPopupContent(`${label} (Diseret):<br>${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`).openPopup();
    });

    return marker;
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

  async saveLocations() {
    await this.calculateRoute(); // otomatis hitung rute dan simpan
  }

  async calculateRoute() {
    if (!this.startCoords || !this.destCoords) {
      alert('Koordinat belum lengkap');
      return;
    }

    try {
      const token = localStorage.getItem('token');
      const response = await axios.post(
        'http://localhost:8000/api/route',
        {
          coordinates: [
            [this.startCoords.lng, this.startCoords.lat],
            [this.destCoords.lng, this.destCoords.lat],
          ]
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          }
        }
      );

      const geojson = response.data.route_geojson;
      const distanceKm = response.data.distance_km;
      const durationMin = response.data.duration_min;
      const totalPrice = response.data.total_price;

      L.geoJSON(geojson, {
        style: {
          color: 'blue',
          weight: 4
        }
      }).addTo(this.map!);

      alert(`✅ Rute berhasil!\nJarak: ${distanceKm} km\nDurasi: ${durationMin} menit\nTarif: Rp${totalPrice}`);

      await this.saveLocationsWithTarif(distanceKm, durationMin, totalPrice);
    } catch (error) {
      console.error('❌ Gagal menghitung rute:', error);
      alert('Gagal menampilkan rute');
    }
  }

  async saveLocationsWithTarif(distanceKm: number, durationMin: number, totalPrice: number) {
    if (!this.startCoords || !this.destCoords) {
      alert('Lokasi belum lengkap');
      return;
    }

    try {
      const token = localStorage.getItem('token');
      await axios.post(
        'http://localhost:8000/api/customer-location',
        {
          start_lat: this.startCoords.lat,
          start_lng: this.startCoords.lng,
          dest_lat: this.destCoords.lat,
          dest_lng: this.destCoords.lng,
          start_address: this.startQuery,
          dest_address: this.destQuery,
          distance_km: distanceKm,
          duration_min: durationMin,
          total_price: totalPrice,
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          }
        }
      );

      console.log('✅ Lokasi dan tarif berhasil disimpan ke database.');
    } catch (error) {
      console.error('❌ Gagal menyimpan lokasi & tarif:', error);
      alert('❌ Gagal menyimpan data ke server');
    }
  }
}
