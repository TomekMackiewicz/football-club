import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { switchMap } from 'rxjs/operators';
import { FormBuilder, Validators } from '@angular/forms';

import { TrainerService } from '../trainer.service';
import { AlertService } from '../../alert/alert.service';
import { Trainer } from '../model/trainer';
import { TRAINER_STATUS } from '../../constants/trainerStatus';

@Component({
    selector: 'app-trainer-edit',
    templateUrl: './trainer-edit.component.html'
})
export class TrainerEditComponent implements OnInit {
    
    trainer: Trainer;
    trainerStatus = TRAINER_STATUS;

    trainerForm = this.fb.group({
        id: [''],
        first_name: ['', Validators.required],
        last_name: ['', Validators.required],
        email: ['', Validators.email],
        status: [''],
        trainings: [{ value: null, disabled: true }]
    });

    constructor(
        private router: Router,
        private route: ActivatedRoute,
        private trainerService: TrainerService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }

    ngOnInit(): void {
        this.route.params.forEach((params: Params) => {
            if (params['id'] !== undefined) {
                const id = +params['id'];
                this.trainerService.getTrainer(id).subscribe(trainer => {
                    this.trainer = trainer;
                    this.trainerForm.setValue(this.trainer);
                });
                // co jeśli błąd w subscribe?
            } else {
                this.alertService.error('error');//?
            }
        });
    }

    updateTrainer() {
        return this.trainerService.updateTrainer(this.trainerForm.value).subscribe(
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
