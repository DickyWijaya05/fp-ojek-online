import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DasboardHomePage } from './dasboard-home.page';

describe('DasboardHomePage', () => {
  let component: DasboardHomePage;
  let fixture: ComponentFixture<DasboardHomePage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DasboardHomePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
