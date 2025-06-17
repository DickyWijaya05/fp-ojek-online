import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoginCostumerPage } from './login-costumer.page';

const routes: Routes = [
  {
    path: '',
    component: LoginCostumerPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LoginCostumerPageRoutingModule {}
