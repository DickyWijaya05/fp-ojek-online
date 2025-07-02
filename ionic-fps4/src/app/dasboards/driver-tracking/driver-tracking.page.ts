import { Component } from '@angular/core';
import * as L from 'leaflet';
import { Geolocation } from '@capacitor/geolocation';
import axios from 'axios';
import type { GeoJsonObject } from 'geojson';

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
  driverId: number | null = null;
  incomingOrder: any = null;
  stepStatus: 'accepted' | 'pickupReached' | 'toDestination' | 'completed' | null = null;
  pickupMarker: L.Marker | undefined;
  destinationMarker: L.Marker | undefined;
  routeLine: L.Layer | undefined;
  selectedMethod: string = '';


  async ionViewDidEnter() {
    const success = await this.ambilProfilDriver(); // ⬅️ Panggil di awal
    if (!success) return;
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
    if (!token) { alert('Token tidak ditemukan. Silakan login ulang.'); return; }

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

  async ambilProfilDriver() {
    const token = localStorage.getItem('driver_token');
    if (!token) {
      alert('Token tidak ditemukan. Silakan login ulang.');
      return false;
    }

    try {
      const res = await axios.get('http://localhost:8000/api/driver/profile', {
        headers: { Authorization: `Bearer ${token}` }
      });

      console.log('🧾 Data profil driver:', res.data);

      // Cek jika struktur nested
      this.driverId = res.data.driver?.id ?? res.data.id;

      if (!this.driverId) {
        throw new Error('ID driver tidak ditemukan di response');
      }

      return true;
    } catch (err) {
      console.error('❌ Gagal ambil profile driver:', err);
      alert('Gagal mengambil data driver. Silakan login ulang.');
      return false;
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

      // ✅ Kirim status: OTW ke lokasi jemput
      await axios.post(`http://localhost:8000/api/driver/order-status/${this.incomingOrder.id}`, {
        status: 'on_the_way'
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      alert('✅ Order berhasil diterima dan status OTW dikirim!');
      this.acceptedOrderId = this.incomingOrder.id;
      this.stepStatus = 'accepted';

      const routeRes = await axios.post('http://localhost:8000/api/route', {
        coordinates: [
          [this.currentLng, this.currentLat], // Driver pos
          [parseFloat(this.incomingOrder.start_lng), parseFloat(this.incomingOrder.start_lat)] // Penumpang pos
        ]
      });

      const geojson = routeRes.data.route_geojson;
      this.incomingOrder.route_geojson = geojson;

      alert('✅ Order diterima & rute berhasil diambil!');
      this.showRouteToPickup();

    } catch (err) {
      console.error('❌ Gagal menerima order atau ambil rute:', err);
      alert('❌ Gagal menerima order.');
    }
  }

  showRouteToPickup() {
    if (!this.incomingOrder) return;

    if (this.routeLine) this.map?.removeLayer(this.routeLine);
    if (this.pickupMarker) this.map?.removeLayer(this.pickupMarker);

    try {
      const rawGeoJson = this.incomingOrder.route_geojson;

      const geojson = typeof rawGeoJson === 'string'
        ? JSON.parse(rawGeoJson)
        : rawGeoJson;

      this.routeLine = L.geoJSON(geojson, {
        style: { color: 'blue', weight: 4 }
      }).addTo(this.map!);

      const pickupLat = parseFloat(this.incomingOrder.start_lat);
      const pickupLng = parseFloat(this.incomingOrder.start_lng);
      this.pickupMarker = L.marker([pickupLat, pickupLng]).addTo(this.map!);

      if ('getBounds' in this.routeLine && typeof this.routeLine.getBounds === 'function') {
        this.map?.fitBounds(this.routeLine.getBounds());
      }

    } catch (err) {
      console.warn('❌ Gagal parsing route_geojson, fallback ke garis lurus:', err);

      const driverLat = this.currentLat;
      const driverLng = this.currentLng;
      const pickupLat = parseFloat(this.incomingOrder.start_lat);
      const pickupLng = parseFloat(this.incomingOrder.start_lng);

      this.routeLine = L.polyline([[driverLat, driverLng], [pickupLat, pickupLng]], { color: 'blue' }).addTo(this.map!);
      this.pickupMarker = L.marker([pickupLat, pickupLng]).addTo(this.map!);

      if ('getBounds' in this.routeLine && typeof this.routeLine.getBounds === 'function') {
        this.map?.fitBounds(this.routeLine.getBounds());
      }
    }
  }

  async sudahSampaiJemput() {
    if (!this.incomingOrder) return;

    const token = localStorage.getItem('driver_token');
    try {
      await axios.post(`http://localhost:8000/api/driver/order-status/${this.incomingOrder.id}`, {
        status: 'pickupReached'
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      // ✅ Sukses kirim ke backend
      this.stepStatus = 'pickupReached';

      // 🔊 Mainkan suara lokal untuk konfirmasi
      const audio = new Audio('assets/sound/arrived.mp3');
      try {
        await audio.play();
      } catch (err) {
        console.warn('Gagal play suara:', err);
      }

      alert('✅ Notifikasi "Sudah Sampai Jemput" dikirim ke penumpang!');
    } catch (err) {
      console.error('❌ Gagal kirim status pickupReached:', err);
      alert('❌ Gagal mengirim notifikasi ke penumpang.');
    }
  }



  async proceedToDestination() {
    if (!this.incomingOrder) return;

    this.stepStatus = 'toDestination';

    const orderId = this.incomingOrder.id;

    try {
      const token = localStorage.getItem('driver_token');
      const res = await axios.get(`http://localhost:8000/api/driver/route-to-destination/${orderId}`, {
        headers: { Authorization: `Bearer ${token}` }
      });


      const geojson = res.data.route_geojson;

      // Bersihkan rute lama & marker lama
      if (this.routeLine) this.map?.removeLayer(this.routeLine);
      if (this.destinationMarker) this.map?.removeLayer(this.destinationMarker);

      // Tambahkan rute dari jemput ke tujuan
      this.routeLine = L.geoJSON(geojson, {
        style: { color: 'green', weight: 4 }
      }).addTo(this.map!);

      // Marker tujuan akhir
      const destLat = parseFloat(this.incomingOrder.dest_lat);
      const destLng = parseFloat(this.incomingOrder.dest_lng);
      this.destinationMarker = L.marker([destLat, destLng]).addTo(this.map!);

      // Auto zoom ke rute
      if (this.routeLine && 'getBounds' in this.routeLine && typeof this.routeLine.getBounds === 'function') {
        this.map?.fitBounds(this.routeLine.getBounds());
      }

    } catch (error) {
      console.error('❌ Gagal ambil rute ke tujuan:', error);
      alert('⚠️ Gagal menampilkan rute ke tujuan.');
    }
  }

  async completeTrip() {

    if (!this.incomingOrder) return;
    const token = localStorage.getItem('driver_token');
    const orderId = this.incomingOrder.id;

    try {
      await axios.post(`http://localhost:8000/api/driver/order-status/${orderId}`, {
        status: 'completed'
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      console.log('✅ Status Order Di Ubah Ke Komplit');
      this.stepStatus = 'completed';
      alert('🎉 Perjalanan selesai! Penumpang akan diminta membayar.');

    } catch (err) {
      console.error('❌ Gagal kirim notifikasi pembayaran ke pelanggan:', err);
    }
  }

  async konfirmasiPembayaran() {
    if (!this.incomingOrder) return;

    if (!this.selectedMethod) {
      alert('⚠️ Silakan pilih metode pembayaran terlebih dahulu.');
      return;
    }

    const token = localStorage.getItem('driver_token');

    if (!this.driverId) {
      const success = await this.ambilProfilDriver();
      if (!success || !this.driverId) {

        alert('Tidak bisa mendapatkan ID driver. Silakan coba lagi.');

        return;
      }
    }

    const payload = {
      order_id: this.incomingOrder.id,
      customer_id: this.incomingOrder.user.id,
      driver_id: this.driverId, // pastikan disiapkan sebelumnya
      distance: this.incomingOrder.distance_km,
      duration: this.incomingOrder.duration_min,
      total_price: this.incomingOrder.total_price,
      metode: this.selectedMethod,
    };

    try {
      await axios.post('http://localhost:8000/api/transactions', payload, {
        headers: { Authorization: `Bearer ${token}` }
      });

      alert('✅ Transaksi berhasil disimpan!');
      console.log('📦 Transaksi dikirim:', payload);

      // opsional: resetTracking atau redirect ke halaman lain
      this.resetTracking();

    } catch (err) {
      console.error('❌ Gagal simpan transaksi:', err);
      alert('Gagal menyimpan transaksi. Silakan coba lagi.');
      console.log('📤 Payload transaksi:', payload);
      console.log('📤 Payload driver_id:', this.driverId);

    }
  }


  resetTracking() {
    this.stepStatus = null;
    this.acceptedOrderId = null;
    this.incomingOrder = null;

    if (this.routeLine) this.map?.removeLayer(this.routeLine);
    if (this.pickupMarker) this.map?.removeLayer(this.pickupMarker);
    if (this.destinationMarker) this.map?.removeLayer(this.destinationMarker);
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

      const orders = Array.isArray(response.data?.data) ? response.data.data : [];

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

          // alert(`🚨 Order Masuk dari ${firstNewOrder.user?.name ?? 'Pelanggan'}\nTujuan: ${firstNewOrder.dest_address}`);
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

