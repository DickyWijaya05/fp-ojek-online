import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RegistersPage } from './registers.page';

const routes: Routes = [
  {
    path: '',
    component: RegistersPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class RegistersPageRoutingModule {}
