import { Component, OnInit } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { filter } from 'rxjs/operators';

@Component({
  standalone: false,
  selector: 'app-dasboards',
  templateUrl: './dasboards.page.html',
  styleUrls: ['./dasboards.page.scss'],
})
export class DasboardsPage {
  selectedTab: string = 'home';

  constructor(private router: Router) { }

  ngOnInit() {
    this.router.events
      .pipe(filter((event) => event instanceof NavigationEnd))
      .subscribe((event: any) => {
        const currentUrl = event.urlAfterRedirects;
        if (currentUrl.includes('/homes')) {
          this.selectedTab = 'homes';
        } else if (currentUrl.includes('/activitys')) {
          this.selectedTab = 'activitys';
        } else if (currentUrl.includes('/accounts')) {
          this.selectedTab = 'accounts';
        } else if (currentUrl.includes('/chats')) {
          this.selectedTab = 'chats';
        }
      });
  }

  setSelectedTab(tab: string) {
    this.selectedTab = tab;
  }
}
