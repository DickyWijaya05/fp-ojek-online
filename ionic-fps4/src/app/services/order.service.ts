import { Injectable } from '@angular/core';
import axios from 'axios';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class OrderService {
  private apiUrl = `${environment.apiUrl}`;

  async createOrder(data: any, token: string) {
    try {
      const response = await axios.post(`${this.apiUrl}/order`, data, {
        headers: {
          Authorization: `Bearer ${token}`,
        }
      });
      return response;
    } catch (error: any) {
      throw error.response?.data?.message || 'Gagal membuat order';
    }
  }
}
