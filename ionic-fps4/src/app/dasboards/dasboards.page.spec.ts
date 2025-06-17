import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DasboardsPage } from './dasboards.page';

describe('DasboardsPage', () => {
  let component: DasboardsPage;
  let fixture: ComponentFixture<DasboardsPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DasboardsPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
