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
        id: [''],
        small_file_size: [''],
        medium_file_size: [''],
        large_file_size: ['']
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
                this.configForm.setValue(this.config);
            },
            error => {
                console.log(error);
            }
        );        
    }
    
    saveConfig() {
        if (this.config.id === -1) {
            this.createConfig();
        } else {
            this.updateConfig();
        }
    }

    createConfig() {
        return this.configService.createConfig(this.configForm.value).subscribe(
            success => {
                this.alertService.success(success, true);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

    updateConfig() {
        return this.configService.updateConfig(this.configForm.value).subscribe(
            success => {
                this.alertService.success(success, true);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

}
