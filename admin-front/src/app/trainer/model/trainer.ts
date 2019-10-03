import { Training } from '../../training/model/training';

export interface Trainer {
    id: number;
    firstName: string;
    lastName: string;
    email: string;
    status: boolean;
    trainings: Array<Training>
}
