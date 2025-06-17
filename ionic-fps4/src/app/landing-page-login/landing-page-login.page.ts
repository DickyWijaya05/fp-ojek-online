import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  standalone:false,
  selector: 'app-landing-page-login',
  templateUrl: './landing-page-login.page.html',
  styleUrls: ['./landing-page-login.page.scss'],
})
export class LandingPageLoginPage {
  constructor(private router: Router) {}

  goToRegister() {
    this.router.navigateByUrl('/register-option');
  }

  goToLogin() {
    this.router.navigateByUrl('/login-option');
  }
}
