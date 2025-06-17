import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LoginCostumerPage } from './login-costumer.page';

describe('LoginCostumerPage', () => {
  let component: LoginCostumerPage;
  let fixture: ComponentFixture<LoginCostumerPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(LoginCostumerPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
