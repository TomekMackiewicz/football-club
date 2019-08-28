import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder } from '@angular/forms';
import { ConfigService } from './config.service';
import { AlertService } from '../alert/alert.service';
import { Config } from '../model/config';

@Component({
    selector: 'app-config',
    templateUrl: './config.component.html'
})
export class ConfigComponent implements OnInit {
    config: Config;
    configForm = this.fb.group({
        smallFileSize: [''],
        mediumFileSize: [''],
        largeFileSize: ['']
    });

    constructor(
        private router: Router,
        private configService: ConfigService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }
    
    ngOnInit() {
        this.getConfig();
    }
    
    getConfig() {
        this.configService.getConfig().subscribe(
            resp => {
                this.config = resp;
            },
            error => {
                console.log(error);
            }
        );        
    }

    editConfig() {
        return this.configService.editConfig(this.configForm.value).subscribe(
            success => {
                this.alertService.success(success, true);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

}
