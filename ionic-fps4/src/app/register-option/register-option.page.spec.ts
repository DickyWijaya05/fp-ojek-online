import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RegisterOptionPage } from './register-option.page';

describe('RegisterOptionPage', () => {
  let component: RegisterOptionPage;
  let fixture: ComponentFixture<RegisterOptionPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(RegisterOptionPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
