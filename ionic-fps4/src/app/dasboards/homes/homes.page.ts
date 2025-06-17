import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';


@Component({
  standalone:false,
  selector: 'app-homes',
  templateUrl: './homes.page.html',
  styleUrls: ['./homes.page.scss'],
})
export class HomesPage implements OnInit {
  user: any = null;

  constructor(private router: Router, private authService: AuthService) {}

  ngOnInit() {
    this.authService.user.subscribe(userData => {
      if (userData) {
        this.user = userData;
      } else {
        // fallback localStorage jika BehaviorSubject kosong (misal reload page)
        const localUser = localStorage.getItem('user');
        this.user = localUser ? JSON.parse(localUser) : null;
      }
    });
  }

  goToLokasi() {
    this.router.navigate(['/driver-tracking']);
  }
}
