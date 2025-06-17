import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CanceledPage } from './canceled.page';

const routes: Routes = [
  {
    path: '',
    component: CanceledPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CanceledPageRoutingModule {}
