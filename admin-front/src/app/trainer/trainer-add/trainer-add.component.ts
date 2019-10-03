import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, Validators } from '@angular/forms';

import { TrainerService } from '../trainer.service';
import { AlertService } from '../../alert/alert.service';
import { TRAINER_STATUS } from '../../constants/trainerStatus';

@Component({
    selector: 'app-trainer-add',
    templateUrl: './trainer-add.component.html'
})
export class TrainerAddComponent {
    
    trainerStatus = TRAINER_STATUS;

    trainerForm = this.fb.group({
        first_name: ['', Validators.required],
        last_name: ['', Validators.required],
        email: ['', Validators.email],
        status: ['']
    });

    constructor(
        private router: Router,
        private trainerService: TrainerService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }

    addTrainer() {
        return this.trainerService.addTrainer(this.trainerForm.value).subscribe(
            success => {
                this.alertService.success(success, true);
                this.router.navigate(['/admin/trainers/list']);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

}
