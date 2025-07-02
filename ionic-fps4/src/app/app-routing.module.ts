import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'landing-page-login', // ← Awal buka langsung ke halaman login
    pathMatch: 'full'
  },
  {
    path: 'login',
    loadChildren: () => import('./login/login.module').then(m => m.LoginPageModule)
  },
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then(m => m.HomePageModule)
  },
  // {
  //   path: 'register',
  //   loadChildren: () => import('./register/register.module').then(m => m.RegisterPageModule)
  // },
  // {
  //   path: 'login-content',
  //   loadChildren: () => import('./login-content/login-content.module').then(m => m.LoginContentPageModule)
  // },
  // {
  //   path: 'otp-input',
  //   loadChildren: () => import('./otp-input/otp-input.module').then(m => m.OtpInputPageModule)
  // },
  {
    path: 'dasboard',
    loadChildren: () => import('./dasboard/dasboard.module').then(m => m.DasboardPageModule)
  },
  {
    path: 'dasboard-home',
    loadChildren: () => import('./dasboard/dasboard-home/dasboard-home.module').then(m => m.DasboardHomePageModule)
  },
  {
    path: 'activity',
    loadChildren: () => import('./dasboard/activity/activity.module').then(m => m.ActivityPageModule)
  },
  {
    path: 'account',
    loadChildren: () => import('./dasboard/account/account.module').then(m => m.AccountPageModule)
  },
  {
    path: 'chat',
    loadChildren: () => import('./dasboard/chat/chat.module').then(m => m.ChatPageModule)
  },
  {
    path: 'tracking-lokasi',
    loadChildren: () => import('./tracking-lokasi/tracking-lokasi.module').then(m => m.TrackingLokasiPageModule)
  },
  {
    path: 'cancel-ride',
    loadChildren: () => import('./cancel-ride/cancel-ride.module').then(m => m.CancelRidePageModule)
  },
  {
    path: 'confirm-rating',
    loadChildren: () => import('./confirm-rating/confirm-rating.module').then(m => m.ConfirmRatingPageModule)
  },
  {
    path: 'form-register',
    loadChildren: () => import('./form-register/form-register.module').then(m => m.FormRegisterPageModule)
  },
  {
    path: 'canceled',
    loadChildren: () => import('./canceled/canceled.module').then(m => m.CanceledPageModule)
  },
  {
    path: 'register-option',
    loadChildren: () => import('./register-option/register-option.module').then(m => m.RegisterOptionPageModule)
  },
  {
    path: 'registers',
    loadChildren: () => import('./registers/registers.module').then(m => m.RegistersPageModule)
  },
  {
    path: 'document-driver',
    loadChildren: () => import('./document-driver/document-driver.module').then(m => m.DocumentDriverPageModule)
  },
  {
    path: 'dasboards',
    loadChildren: () => import('./dasboards/dasboards.module').then(m => m.DasboardsPageModule)
  },
  {

    path: 'accounts',
    loadChildren: () => import('./dasboards/accounts/accounts.module').then(m => m.AccountsPageModule)
  },
  {
    path: 'chats',
    loadChildren: () => import('./dasboards/chats/chats.module').then(m => m.ChatsPageModule)
  },
  {
    path: 'homes',
    loadChildren: () => import('./dasboards/homes/homes.module').then(m => m.HomesPageModule)
  },
  {
    path: 'activitys',
    loadChildren: () => import('./dasboards/activitys/activitys.module').then(m => m.ActivitysPageModule)
  },
  {
    path: 'login-costumer',
    loadChildren: () => import('./login-costumer/login-costumer.module').then(m => m.LoginCostumerPageModule)
  },
  {
    path: 'driver-tracking',
    loadChildren: () => import('./dasboards/driver-tracking/driver-tracking.module').then(m => m.DriverTrackingPageModule)
  },
  {
    path: 'user-tracking',
    loadChildren: () => import('./dasboard/user-tracking/user-tracking.module').then(m => m.UserTrackingPageModule)
  },
  {
    path: 'landing-page-login',
    loadChildren: () => import('./landing-page-login/landing-page-login.module').then(m => m.LandingPageLoginPageModule)
  },
  {
    path: 'login-option',
    loadChildren: () => import('./login-option/login-option.module').then(m => m.LoginOptionPageModule)
  },
  {
    path: 'payment/:id',
    loadChildren: () => import('./payment/payment.module').then(m => m.PaymentPageModule)
  },
  {
    path: 'rating',
    loadChildren: () => import('./rating/rating.module').then(m => m.RatingPageModule)
  }

];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
