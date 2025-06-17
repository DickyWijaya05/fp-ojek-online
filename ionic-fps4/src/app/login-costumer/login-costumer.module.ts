import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LoginCostumerPageRoutingModule } from './login-costumer-routing.module';

import { LoginCostumerPage } from './login-costumer.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LoginCostumerPageRoutingModule
  ],
  declarations: [LoginCostumerPage]
})
export class LoginCostumerPageModule {}
