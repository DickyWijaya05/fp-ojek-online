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
  driverStatus: 'idle' | 'searching' | 'found' | 'info' | 'ordered' | 'accepted' | 'completed' = 'idle';
  selectedDriver: any = null;
  orderId: number | null = null;
  orderPollingInterval: any;
  orderStatus: string | null = null;
  routeLine: L.GeoJSON | null = null;
  driverMarker: L.Marker | undefined;
  destinationMarker: L.Marker | null = null;
  driverTrackingInterval: any;
  pickupSoundPlayed = false;
  incomingOrder: any = null;
  hasDrawnToDestination = false;

  constructor(private orderService: OrderService, private toastCtrl: ToastController) { }

  async ionViewDidEnter() {
    const position = await Geolocation.getCurrentPosition();
    ({
      enableHighAccuracy: true,
      timeout: 15000,  // 15 detik
      maximumAge: 0
    });
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

    Geolocation.watchPosition({ enableHighAccuracy: true, timeout: 15000, maximumAge: 0 },
      (position, err) => {
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

      if (this.routeLine) {
        this.map?.removeLayer(this.routeLine);
        this.routeLine = null;
      }

      const response = await axios.post('http://localhost:8000/api/route', {
        coordinates: [
          [this.startCoords.lng, this.startCoords.lat],
          [this.destCoords.lng, this.destCoords.lat],
        ]
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      const { route_geojson, distance_km, duration_min, total_price } = response.data;

      this.routeLine = L.geoJSON(route_geojson, {
        style: { color: 'blue', weight: 4 }
      }).addTo(this.map!);

      if (this.routeLine && 'getBounds' in this.routeLine) {
        this.map?.fitBounds(this.routeLine.getBounds());
      }
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
      console.log('📦 Order ID:', this.orderId);
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
    console.log('[Polling] Mulai dengan orderId:', this.orderId, 'token:', token);
    if (!this.orderId || !token) return;

    this.orderPollingInterval = setInterval(async () => {
      try {
        const res = await axios.get(`http://localhost:8000/api/order/${this.orderId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        const order = res.data.data;


        switch (order.status) {
          case 'accepted':
            this.driverStatus = 'accepted';
            this.orderStatus = 'accepted';
            this.showToast('✅ Driver menuju lokasi Anda');
            this.startTrackingDriver(order.driver_id);
            break;

          // tambahkan ini
          case 'on_the_way':
            if (this.orderStatus !== 'on_the_way') {
              this.driverStatus = 'accepted';
              this.orderStatus = 'on_the_way';
              this.showToast('🛵 Driver sudah menerima order & OTW!');
              this.startTrackingDriver(order.driver_id);
              this.drawRouteFromDriverToPickup(order);
            }
            break;
          case 'pickupReached':
            if (this.orderStatus !== 'pickupReached') {
              this.driverStatus = 'accepted';
              this.orderStatus = 'pickupReached';
              this.showToast('🚕 Driver sudah sampai, silakan naik!');
              if (!this.pickupSoundPlayed) {
                try {
                  const audio = new Audio('assets/sound/nyampe.mp3');
                  await audio.play();
                  this.pickupSoundPlayed = true;
                } catch (err) {
                  console.warn('❌ Gagal play suara:', err);
                }
              }
            }
            break;
          case 'toDestination':
            this.driverStatus = 'accepted';
            this.orderStatus = 'toDestination';
            if (!this.hasDrawnToDestination) {
              this.drawRoutePickupToDestination(order);
              this.hasDrawnToDestination = true;
              this.showToast('🚀 Dalam perjalanan ke tujuan...');
            }
            break;

          case 'completed':
            this.driverStatus = 'completed';
            this.orderStatus = 'completed';
            this.showToast('🎉 Perjalanan selesai. Terima kasih!');
            clearInterval(this.driverTrackingInterval);
            clearInterval(this.orderPollingInterval);
            break;
        }
      } catch (error) {
        console.error('❌ Gagal polling status order:', error);
      }
    }, 3000);
  }


  getReadableStatus(status: string): string {
    switch (status) {
      case 'accepted': return 'Driver menuju Anda';
      case 'pickupReached': return 'Driver sudah tiba';
      case 'toDestination': return 'Menuju tujuan';
      case 'completed': return 'Selesai';
      default: return status;
    }
  }

  drawRouteFromDriverToPickup(order: any) {
    if (!order || !order.driver_lat || !order.driver_lng) return;


    const coords = [
      [order.driver_lat, order.driver_lng],
      [order.start_lat, order.start_lng],
    ];

    if (this.routeLine) {
      this.map?.removeLayer(this.routeLine);
      this.routeLine = null;
    }

    if (this.destinationMarker) {
      this.map?.removeLayer(this.destinationMarker);
      this.destinationMarker = null;
    }


    axios.post('http://localhost:8000/api/route', {
      coordinates: coords.map(([lat, lng]) => [lng, lat])
    }).then(res => {
      const geojson = res.data.route_geojson;

      this.routeLine = L.geoJSON(geojson, {
        style: { color: 'orange', weight: 4 }
      }).addTo(this.map!);

      if (this.routeLine && 'getBounds' in this.routeLine) {
        this.map?.fitBounds(this.routeLine.getBounds());
      }
    }).catch(err => {
      console.error('❌ Gagal gambar rute dari driver ke penjemputan:', err);
    });
  }


  drawRoutePickupToDestination(order: any) {
    if (!order) return;

    const coords = [
      [order.start_lat, order.start_lng],
      [order.dest_lat, order.dest_lng],
    ];
    if (this.routeLine) {
      this.map?.removeLayer(this.routeLine);
      this.routeLine = null;
    }
    if (this.destinationMarker) {
      this.map?.removeLayer(this.destinationMarker);
      this.destinationMarker = null;
    }
    if (this.driverMarker) {
      this.map?.removeLayer(this.driverMarker);
      this.driverMarker = undefined;
    }
    if (this.startMarker) {
      this.map?.removeLayer(this.startMarker);
      this.startMarker = undefined;
    }

    // Tambahkan rute (bisa request ulang atau pakai existing geojson jika tersedia)
    axios.post('http://localhost:8000/api/route', {
      coordinates: coords.map(([lat, lng]) => [lng, lat])
    }).then(res => {
      const geojson = res.data.route_geojson;

      this.routeLine = L.geoJSON(geojson, {
        style: { color: 'orange', weight: 4 }
      }).addTo(this.map!);

      if (this.routeLine && 'getBounds' in this.routeLine) {
        this.map?.fitBounds(this.routeLine.getBounds());
      }
    }).catch(err => {
      console.error('❌ Gagal gambar rute tujuan:', err);
    });
  }


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

  async lanjutKeTujuan() {
    if (this.orderStatus === 'pickupReached' && this.orderId) {
      try {
        const token = localStorage.getItem('token');

        // Kirim update status ke backend
        await axios.post(`http://localhost:8000/api/customer/order-status/${this.orderId}`, {
          status: 'toDestination'
        }, {
          headers: { Authorization: `Bearer ${token}` }
        });

        this.orderStatus = 'toDestination';
        this.drawRoutePickupToDestination(this.incomingOrder);

      } catch (error) {
        console.error('❌ Gagal update status ke tujuan:', error);
        this.showToast('❌ Gagal mulai perjalanan ke tujuan');
      }
    }
  }
}