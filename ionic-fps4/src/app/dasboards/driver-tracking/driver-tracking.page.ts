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
  acceptedOrderId: number | null = null;
  incomingOrder: any = null;
  stepStatus: 'accepted' | 'pickupReached' | 'toDestination' | 'completed' | null = null;
  pickupMarker: L.Marker | undefined;
  destinationMarker: L.Marker | undefined;
  routeLine: L.Polyline | undefined;


  async ionViewDidEnter() {
    const position = await Geolocation.getCurrentPosition({ enableHighAccuracy: true });

    this.currentLat = position.coords.latitude;
    this.currentLng = position.coords.longitude;

    this.map = L.map('map').setView([this.currentLat, this.currentLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
    }).addTo(this.map);

    const driverIcon = L.icon({
      iconUrl: 'assets/alert-icon.svg',
      iconSize: [32, 32],
      iconAnchor: [16, 32],
      popupAnchor: [0, -32],
    });

    this.driverMarker = L.marker([this.currentLat, this.currentLng], {
      icon: driverIcon,
      draggable: true,
    })
      .addTo(this.map)
      .bindPopup(`📍 Lat: ${this.currentLat.toFixed(5)}<br>Lng: ${this.currentLng.toFixed(5)}`)
      .openPopup()
      .on('dragend', async (e: any) => {
        const newLatLng = e.target.getLatLng();
        this.currentLat = newLatLng.lat;
        this.currentLng = newLatLng.lng;

        this.driverMarker?.setPopupContent(
          `📍 Lat: ${newLatLng.lat.toFixed(5)}<br>Lng: ${newLatLng.lng.toFixed(5)}`
        ).openPopup();

        this.map?.setView(newLatLng);
        await this.simpanLokasi(); // ✅ simpan lokasi setelah digeser
      });

    setTimeout(() => {
      this.map?.invalidateSize();
    }, 100);

    await this.simpanLokasi(); // ✅ simpan lokasi saat masuk halaman
    this.loadIncomingOrder();
    setInterval(() => this.loadIncomingOrder(), 10000); // Polling tiap 10 detik
    // ✅ Tambahkan ini untuk menyimpan lokasi otomatis tiap 15 detik
    setInterval(() => this.simpanLokasi(), 15000);
  }

  async ionViewWillLeave() {
    const token = localStorage.getItem('driver_token');
    if (!token) return;

    try {
      await axios.post('http://localhost:8000/api/driver-status',
        { status: 'offline' },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      console.log('🚫 Driver keluar, status jadi offline');
    } catch (err) {
      console.warn('Gagal set status offline', err);
    }
  }

  async simpanLokasi() {
    const token = localStorage.getItem('driver_token');
    if (!token) {
      alert('Token tidak ditemukan. Silakan login ulang.');
      return;
    }

    try {
      const response = await axios.post('http://localhost:8000/api/driver-location',
        {
          latitude: this.currentLat,
          longitude: this.currentLng,
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      console.log('📍 Lokasi driver disimpan:', response.data);
    } catch (error) {
      console.error('❌ Gagal simpan lokasi:', error);
    }
  }

  async acceptOrder() {
    if (!this.incomingOrder) return;

    const token = localStorage.getItem('driver_token');
    try {
      await axios.post(`http://localhost:8000/api/driver/accept-order/${this.incomingOrder.id}`,
        {
          driver_lat: this.currentLat,
          driver_lng: this.currentLng,
        },
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      alert('✅ Order berhasil diterima!');
      this.acceptedOrderId = this.incomingOrder.id;
      this.stepStatus = 'accepted';
      await this.fetchAcceptedOrderAndDrawRoute();
      // this.showRouteToPickup();
      // this.incomingOrder = null;
    } catch (err) {
      alert('❌ Gagal menerima order.');
    }
  }

   async fetchAcceptedOrderAndDrawRoute() {
    const token = localStorage.getItem('driver_token');
    try {
      const response = await axios.get('http://localhost:8000/api/driver/incoming-order', {
        headers: { Authorization: `Bearer ${token}` },
      });

      if (response.data.data.length > 0) {
        this.incomingOrder = response.data.data[0];

        const { driver_lat, driver_lng, start_lat, start_lng } = this.incomingOrder;

        if (driver_lat && driver_lng && start_lat && start_lng) {
          this.showRouteToPickup();
        } else {
          console.warn('⚠️ Koordinat tidak lengkap.');
        }
      }
    } catch (err) {
      console.error('❌ Gagal ambil order:', err);
    }
  }

  showRouteToPickup() {
    if (!this.incomingOrder) return;

    // Ambil dari database (kolom baru di tabel `orders`)
    const driverLat = parseFloat(this.incomingOrder.driver_lat);
    const driverLng = parseFloat(this.incomingOrder.driver_lng);

    const pickupLat = parseFloat(this.incomingOrder.start_lat);
    const pickupLng = parseFloat(this.incomingOrder.start_lng);

    console.log('📍 Rute dari driver ke pickup point:', {
      driverLat, driverLng, pickupLat, pickupLng
    });

    if (this.routeLine) this.map?.removeLayer(this.routeLine);
    if (this.pickupMarker) this.map?.removeLayer(this.pickupMarker);

    this.routeLine = L.polyline([[driverLat, driverLng], [pickupLat, pickupLng]], { color: 'blue' }).addTo(this.map!);
    this.pickupMarker = L.marker([pickupLat, pickupLng]).addTo(this.map!);
    this.map?.fitBounds(this.routeLine.getBounds());
  }


  proceedToDestination() {
    if (!this.incomingOrder) return;

    this.stepStatus = 'toDestination';

    const pickupLat = parseFloat(this.incomingOrder.start_latitude);
    const pickupLng = parseFloat(this.incomingOrder.start_longitude);
    const destLat = parseFloat(this.incomingOrder.dest_latitude);
    const destLng = parseFloat(this.incomingOrder.dest_longitude);

    if (this.routeLine) this.map?.removeLayer(this.routeLine);
    if (this.destinationMarker) this.map?.removeLayer(this.destinationMarker);

    this.routeLine = L.polyline([[pickupLat, pickupLng], [destLat, destLng]], { color: 'green' }).addTo(this.map!);
    this.destinationMarker = L.marker([destLat, destLng]).addTo(this.map!);

    this.map?.fitBounds(this.routeLine.getBounds());
  }

  completeTrip() {
    this.stepStatus = 'completed';
    alert('🎉 Perjalanan selesai!');
  }


  async rejectOrder() {
    if (!this.incomingOrder) return;

    const token = localStorage.getItem('driver_token');
    try {
      await axios.post(`http://localhost:8000/api/driver/reject-order/${this.incomingOrder.id}`,
        {},
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      alert('❌ Order ditolak.');
      this.incomingOrder = null;
    } catch (err) {
      alert('⚠️ Gagal menolak order.');
    }
  }


  async loadIncomingOrder() {
    console.log('🕵️ Polling order masuk...');

    const token = localStorage.getItem('driver_token');
    if (!token) {
      console.warn('🚫 Token tidak ditemukan');
      return;
    }

    try {
      const response = await axios.get('http://localhost:8000/api/driver/incoming-order', {
        headers: { Authorization: `Bearer ${token}` },
      });

      console.log('📡 Data dari server:', response.data);

      const orders = response.data.data;

      if (orders.length > 0) {
        const firstNewOrder = orders.find((order: any) => {
          const isNotSame = !this.incomingOrder || this.incomingOrder.id !== order.id;
          const isNotAccepted = this.acceptedOrderId === null || this.acceptedOrderId !== order.id;
          return isNotSame && isNotAccepted;
        });

        if (firstNewOrder) {
          const audio = new Audio('assets/sound/notif.mp3');
          try {
            await audio.play();
            console.log('🔊 Notifikasi suara diputar');
          } catch (err) {
            console.warn('⚠️ Gagal memutar suara:', err);
          }

          alert(`🚨 Order Masuk dari ${firstNewOrder.user?.name ?? 'Pelanggan'}\nTujuan: ${firstNewOrder.dest_address}`);
          this.incomingOrder = firstNewOrder;
        }
      } else {
        console.log('ℹ️ Tidak ada order baru');
        // jangan reset incomingOrder kalau sedang jalanin order
        if (!this.acceptedOrderId) {
          this.incomingOrder = null;
        }
      }

    } catch (err: any) {
      console.error('❌ Gagal polling order:', err?.response?.data || err.message);
    }
  }
}

