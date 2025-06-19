import { Injectable } from '@angular/core';
import axios from 'axios';

@Injectable({
  providedIn: 'root'
})
export class OrderService {
  private apiUrl = 'http://localhost:8000/api';

  createOrder(data: any) {
    return axios.post(`${this.apiUrl}/order-request`, data);
  }

  acceptOrder(driver_id: number, order_id: number) {
    return axios.post(`${this.apiUrl}/order-accept`, { driver_id, order_id });
  }

  updateStatus(order_id: number, status: string) {
    return axios.post(`${this.apiUrl}/order-update-status`, { order_id, status });
  }

  getActiveOrder(userId: number) {
    return axios.get(`${this.apiUrl}/order-active/${userId}`);
  }
}
