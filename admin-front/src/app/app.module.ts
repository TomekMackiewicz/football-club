import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { 
    MatSidenavModule, 
    MatCheckboxModule, 
    MatToolbarModule, 
    MatButtonModule, 
    MatIconModule, 
    MatMenuModule 
} from '@angular/material';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

@NgModule({
    declarations: [
        AppComponent
    ],
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        AppRoutingModule,
        FormsModule,
        MatSidenavModule,
        MatCheckboxModule,
        MatToolbarModule,
        MatButtonModule,
        MatIconModule,
        MatMenuModule
    ],
    providers: [],
    bootstrap: [AppComponent]
})
export class AppModule { }
