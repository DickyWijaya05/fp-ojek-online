import { Component } from '@angular/core';
import { addIcons } from 'ionicons';
import { library, playCircle, radio, search } from 'ionicons/icons';

@Component({
  standalone: false,
  selector: 'app-dasboards',
  templateUrl: './dasboards.page.html',
  styleUrls: ['./dasboards.page.scss'],
})
export class DasboardsPage {
  constructor() {
    addIcons({ library, playCircle, radio, search });
  }
}
 