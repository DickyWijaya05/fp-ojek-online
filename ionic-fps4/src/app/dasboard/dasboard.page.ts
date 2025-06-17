import { Component, OnInit } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { filter } from 'rxjs/operators';

@Component({
  standalone: false,
  selector: 'app-dasboard',
  templateUrl: './dasboard.page.html',
  styleUrls: ['./dasboard.page.scss'],
})
export class DasboardPage implements OnInit {
    selectedTab: string = 'home';

  constructor(private router: Router) {}

  ngOnInit() {
    this.router.events
      .pipe(filter((event) => event instanceof NavigationEnd))
      .subscribe((event: any) => {
        const currentUrl = event.urlAfterRedirects;
        if (currentUrl.includes('/home')) {
          this.selectedTab = 'home';
        } else if (currentUrl.includes('/activity')) {
          this.selectedTab = 'activity';
        } else if (currentUrl.includes('/account')) {
          this.selectedTab = 'account';
        } else if (currentUrl.includes('/chat')) {
          this.selectedTab = 'chat';
        }
      });
  }

  setSelectedTab(tab: string) {
    this.selectedTab = tab;
  }

}
 