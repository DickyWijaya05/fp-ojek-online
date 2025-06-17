import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LandingPageLoginPage } from './landing-page-login.page';

const routes: Routes = [
  {
    path: '',
    component: LandingPageLoginPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LandingPageLoginPageRoutingModule {}
