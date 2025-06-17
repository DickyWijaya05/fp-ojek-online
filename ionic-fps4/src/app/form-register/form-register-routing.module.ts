import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { FormRegisterPage } from './form-register.page';

const routes: Routes = [
  {
    path: '',
    component: FormRegisterPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class FormRegisterPageRoutingModule {}
