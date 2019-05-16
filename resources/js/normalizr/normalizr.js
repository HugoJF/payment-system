import {schema} from 'normalizr';

export const constant = new schema.Entity('constants');

export const plugin = new schema.Entity('plugins');

export const field = new schema.Entity('fields');

export const config = new schema.Entity('configs');

export const field_list = new schema.Entity('field_lists', {
    fields: [field],
});

export const list = new schema.Entity('lists', {
    constants: [constant],
    field_list: field_list,
});

export const server = new schema.Entity('servers', {
    configs: [config],
});

export const installation = new schema.Entity('installations', {
    configs: [config],
    plugins: [plugin],
});