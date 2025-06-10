import { trimValue } from '../util/trimValue.js';

export function isNotEmpty(value) {
    return trimValue(value) !== '';
}

export function isMinLength(value, min) {
    return trimValue(value).length >= min;
}

export function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(trimValue(email));
}

export function areEqual(value1, value2) {
    return trimValue(value1) === trimValue(value2);
}