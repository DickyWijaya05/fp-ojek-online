import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RegisterOptionPage } from './register-option.page';

const routes: Routes = [
  {
    path: '',
    component: RegisterOptionPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class RegisterOptionPageRoutingModule {}
