import { Component } from '@angular/core';
import * as L from 'leaflet';
import { Geolocation } from '@capacitor/geolocation';
import axios from 'axios';
import { OrderService } from '../../services/order.service'; // sesuaikan path
import { ToastController } from '@ionic/angular'; // agar alert modern


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

  driverStatus: 'idle' | 'searching' | 'found' | 'info' = 'idle';
  selectedDriver: any = null;
  orderId: number | null = null;
  orderPollingInterval: any;

  driverMarker: L.Marker | undefined;
  driverTrackingInterval: any;



  constructor(private orderService: OrderService, private toastCtrl: ToastController) { }

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
      if (err) return;
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
      if (label.includes('Awal')) this.startCoords = { lat: pos.lat, lng: pos.lng };
      else this.destCoords = { lat: pos.lat, lng: pos.lng };
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
      alert('Gagal mencari lokasi');
    }
  }

  async findNearestDriver() {
    if (!this.startCoords) {
      alert('Lokasi belum tersedia');
      return;
    }

    this.driverStatus = 'searching';

    try {
      const token = localStorage.getItem('token');
      const response = await axios.post('http://localhost:8000/api/nearest-driver', {
        latitude: this.startCoords.lat,
        longitude: this.startCoords.lng,
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      const drivers = response.data.data;
      if (drivers.length === 0) throw new Error('No drivers found');

      this.driverStatus = 'found';
      this.selectedDriver = drivers[0];

      L.marker([drivers[0].latitude, drivers[0].longitude], {
        icon: L.icon({
          iconUrl: 'assets/Logo.png',
          iconSize: [32, 32],
          iconAnchor: [16, 32]
        })
      }).addTo(this.map!)
        .bindPopup(`
          🧍 Nama: ${drivers[0].user?.name ?? 'Unknown'}<br>
          Jarak: ${drivers[0].distance.toFixed(2)} km
        `).openPopup();

    } catch (err) {
      this.driverStatus = 'idle';
      alert('❌ Driver tidak ditemukan');
    }
  }

  showDriverInfo() {
    this.driverStatus = 'info';
  }

  async saveLocations() {
    await this.calculateRoute();
  }

  async calculateRoute() {
    if (!this.startCoords || !this.destCoords) return alert('Koordinat belum lengkap');

    try {
      const token = localStorage.getItem('token');
      const response = await axios.post('http://localhost:8000/api/route', {
        coordinates: [
          [this.startCoords.lng, this.startCoords.lat],
          [this.destCoords.lng, this.destCoords.lat],
        ]
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      const { route_geojson, distance_km, duration_min, total_price } = response.data;

      L.geoJSON(route_geojson, {
        style: { color: 'blue', weight: 4 }
      }).addTo(this.map!);

      alert(`✅ Rute berhasil!\nJarak: ${distance_km} km\nDurasi: ${duration_min} menit\nTarif: Rp${total_price}`);
      await this.saveLocationsWithTarif(distance_km, duration_min, total_price);
    } catch (error) {
      alert('Gagal menampilkan rute');
    }
  }

  async saveLocationsWithTarif(distanceKm: number, durationMin: number, totalPrice: number) {
    if (!this.startCoords || !this.destCoords) return;

    try {
      const token = localStorage.getItem('token');
      await axios.post('http://localhost:8000/api/customer-location', {
        start_lat: this.startCoords.lat,
        start_lng: this.startCoords.lng,
        dest_lat: this.destCoords.lat,
        dest_lng: this.destCoords.lng,
        start_address: this.startQuery,
        dest_address: this.destQuery,
        distance_km: distanceKm,
        duration_min: durationMin,
        total_price: totalPrice,
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      console.log('✅ Lokasi dan tarif disimpan');
    } catch (error) {
      alert('❌ Gagal menyimpan data');
    }
  }

  async orderDriver() {
    if (!this.selectedDriver || !this.startCoords || !this.destCoords) {
      return this.showToast('❌ Data tidak lengkap untuk melakukan order');
    }

    const token = localStorage.getItem('token')!;
    console.log('TOKEN:', token);
    const orderPayload = {
      driver_id: this.selectedDriver.driver_id,
      start_lat: this.startCoords.lat,
      start_lng: this.startCoords.lng,
      dest_lat: this.destCoords.lat,
      dest_lng: this.destCoords.lng,
      start_address: this.startQuery,
      dest_address: this.destQuery,
    };

    try {
      const response = await this.orderService.createOrder(orderPayload, token);

      // Ambil ID order dari response dan mulai polling
      this.orderId = response.data.data.id;
      this.showToast('✅ Order berhasil dikirim, menunggu driver menerima...');
      this.startPollingOrderStatus();

      this.driverStatus = 'idle';
    } catch (err) {
      this.showToast('❌ ' + err);
    }
  }


  startTrackingDriver(driverId: number) {
    const token = localStorage.getItem('token');
    if (!token) return;

    this.driverTrackingInterval = setInterval(async () => {
      try {
        const response = await axios.get(`http://localhost:8000/api/driver/${driverId}/location`, {
          headers: { Authorization: `Bearer ${token}` }
        });

        const { latitude, longitude } = response.data;

        if (!this.driverMarker) {
          this.driverMarker = L.marker([latitude, longitude], {
            icon: L.icon({
              iconUrl: 'assets/driver-icon.png', // ganti jika kamu punya ikon khusus driver
              iconSize: [32, 32],
              iconAnchor: [16, 32],
            })
          }).addTo(this.map!)
            .bindPopup('🛵 Driver sedang menuju Anda')
            .openPopup();
        } else {
          this.driverMarker.setLatLng([latitude, longitude]);
        }

      } catch (error) {
        console.error('❌ Gagal mendapatkan lokasi driver:', error);
      }
    }, 3000); // setiap 3 detik
  }









  startPollingOrderStatus() {
    const token = localStorage.getItem('token');
    if (!this.orderId || !token) return;

    this.orderPollingInterval = setInterval(async () => {
      try {
        const res = await axios.get(`http://localhost:8000/api/order/${this.orderId}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        const order = res.data.data;

        if (order.driver_id && order.status === 'accepted') {
          this.showToast(`✅ Order diterima driver!`);
          clearInterval(this.orderPollingInterval);

          // ✅ Mulai melacak lokasi driver setelah order diterima
          this.startTrackingDriver(order.driver_id);
        } else {
          console.log('⏳ Order masih menunggu driver');
        }

      } catch (error) {
        console.error('❌ Gagal cek status order:', error);
      }
    }, 3000);
  }

  // ⬇️ LETAKKAN showToast DI SINI, DI LUAR FUNGSI LAIN
  async showToast(message: string) {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3000,
      color: 'dark',
      position: 'top',
      buttons: [{ text: 'OK', role: 'cancel' }]
    });
    await toast.present();
  }
}