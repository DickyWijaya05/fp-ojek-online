import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LandingPageLoginPageRoutingModule } from './landing-page-login-routing.module';

import { LandingPageLoginPage } from './landing-page-login.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LandingPageLoginPageRoutingModule
  ],
  declarations: [LandingPageLoginPage]
})
export class LandingPageLoginPageModule {}
