import {schema} from 'normalizr';

export const constant = new schema.Entity('constants');

export const field = new schema.Entity('field');

export const field_list = new schema.Entity('field_lists', {
    fields: [field],
});