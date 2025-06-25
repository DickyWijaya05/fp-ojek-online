import { Injectable } from '@angular/core';
import axios from 'axios';

@Injectable({
  providedIn: 'root'
})
export class OrderService {
  private apiUrl = 'http://localhost:8000/api';

  async createOrder(data: any, token: string) {
    try {
      const response = await axios.post(`${this.apiUrl}/order`, data, {
        headers: {
          Authorization: `Bearer ${token}`,
        }
      });
      return response.data;
    } catch (error: any) {
      throw error.response?.data?.message || 'Gagal membuat order';
    }
  }
}
