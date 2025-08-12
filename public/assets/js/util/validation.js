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

/**
 * Valida que el número telefónico contenga solo dígitos y esté en el rango permitido por la norma E.164.
 * Permite de 10 a 15 dígitos (sin "+" ni espacios).
 */
export function isValidPhone(phone) {
    const trimmed = trimValue(phone);
    const regex = /^\d{10,15}$/;
    return regex.test(trimmed);
}

/**
 * Valida código postal con formato genérico (acepta letras, números y guiones).
 * Solo valida que tenga entre 3 y 10 caracteres alfanuméricos.
 */
export function isValidPostalCode(postalCode) {
    const trimmed = trimValue(postalCode);
    const regex = /^[A-Za-z0-9\- ]{3,10}$/;
    return regex.test(trimmed);
}

/**
 * Verifica si un campo contiene solo letras y espacios.
 * Útil para nombres y campos similares.
 */
export function isAlphabeticWithSpaces(value) {
    const trimmed = trimValue(value);
    const regex = /^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+$/;
    return regex.test(trimmed);
}

export function isRadioChecked(selector) {
    return document.querySelector(selector) !== null;
}

export function validatePositive(value) {
    const num = parseFloat(value);
    return !isNaN(num) && num > 0;
}

export function mustBePositiveInt(value) {
    const num = parseInt(value);
    return Number.isInteger(num) && num >= 1;
}

export function mustBeOptionalBoolean(value, fieldName) {
    if (value === undefined) return null; // checkbox desmarcado = válido
    const allowed = [true, false, '1', '0', 1, 0];
    if (!allowed.includes(value)) {
        return `El campo ${fieldName} debe ser verdadero o falso.`;
    }
    return null;
}

export function isValidTimezone(tz) {
    if (typeof Intl === 'object' && typeof Intl.supportedValuesOf === 'function') {
        const validTimezones = Intl.supportedValuesOf('timeZone');
        return validTimezones.includes(tz);
    }
    // Fallback simple (puedes ampliar con lista estática si quieres)
    const fallback = ['UTC', 'America/New_York', 'Europe/London', 'Asia/Tokyo'];
    return fallback.includes(tz);
}
