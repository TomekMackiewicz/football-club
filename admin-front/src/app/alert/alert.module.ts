import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { TranslateModule, TranslateLoader } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';

import { ApplicationPipesModule } from '../pipes/application-pipes.module';

import { AlertComponent } from './alert.component';

import { AlertService } from './alert.service';

@NgModule({
    imports: [
        CommonModule,
        ApplicationPipesModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient]
            }
        })        
    ],
    declarations: [
        AlertComponent
    ],
    providers: [
        AlertService
    ],
    exports: [
        AlertComponent
    ]
})

export class AlertModule {}

export function HttpLoaderFactory(http: HttpClient) {
    return new TranslateHttpLoader(http, "./assets/i18n/", ".json");
}