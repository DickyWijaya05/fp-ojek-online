import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Storage } from '@ionic/storage-angular';
import { Observable, from } from 'rxjs';
import { switchMap } from 'rxjs/operators';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  private apiUrl = `${environment.apiUrl}/user`;

  constructor(private http: HttpClient, private storage: Storage) {
    this.storage.create();
  }

  getUserData(): Observable<any> {
    return from(this.storage.get('auth_token')).pipe(
      switchMap(token => {
        const headers = new HttpHeaders({
          Authorization: `Bearer ${token}`
        });
        return this.http.get(this.apiUrl, { headers });
      })
    );
  }
}
