import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LandingPageLoginPage } from './landing-page-login.page';

describe('LandingPageLoginPage', () => {
  let component: LandingPageLoginPage;
  let fixture: ComponentFixture<LandingPageLoginPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(LandingPageLoginPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
