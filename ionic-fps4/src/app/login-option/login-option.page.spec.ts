import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LoginOptionPage } from './login-option.page';

describe('LoginOptionPage', () => {
  let component: LoginOptionPage;
  let fixture: ComponentFixture<LoginOptionPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(LoginOptionPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
