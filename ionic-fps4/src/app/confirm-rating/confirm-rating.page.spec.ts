import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ConfirmRatingPage } from './confirm-rating.page';

describe('ConfirmRatingPage', () => {
  let component: ConfirmRatingPage;
  let fixture: ComponentFixture<ConfirmRatingPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ConfirmRatingPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
