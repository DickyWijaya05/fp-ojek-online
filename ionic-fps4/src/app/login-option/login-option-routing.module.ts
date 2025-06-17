import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoginOptionPage } from './login-option.page';

const routes: Routes = [
  {
    path: '',
    component: LoginOptionPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LoginOptionPageRoutingModule {}
