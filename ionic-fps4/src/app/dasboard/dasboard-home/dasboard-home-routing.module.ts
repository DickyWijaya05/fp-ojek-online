import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { DasboardHomePage } from './dasboard-home.page';

const routes: Routes = [
  {
    path: '',
    component: DasboardHomePage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class DasboardHomePageRoutingModule {}
 