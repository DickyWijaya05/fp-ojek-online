import { ComponentFixture, TestBed } from '@angular/core/testing';
import { TrackingLokasiPage } from './tracking-lokasi.page';

describe('TrackingLokasiPage', () => {
  let component: TrackingLokasiPage;
  let fixture: ComponentFixture<TrackingLokasiPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(TrackingLokasiPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
