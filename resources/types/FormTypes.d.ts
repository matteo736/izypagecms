import { HTMLInputTypeAttribute } from "react";
import { UserRoles } from "./auth/UserRoles";

// Definizione base dei campi form
export type Field = {
  label: string;
  name: string;
  type: HTMLInputTypeAttribute;
  options?: string[];
  disabled?: boolean;
}

// Tipo base per i valori dei campi
export type FormFieldValue = string | number | readonly string[];

// Campi base che sono comuni a più form
interface BaseAuthFields {
  email: FormFieldValue;
  password: FormFieldValue;
}

interface UsernameField {
  username: FormFieldValue;
}

interface PasswordConfirmationField {
  password_confirmation: FormFieldValue;
}

// Form generico
export interface Form {
  [key: string]: FormFieldValue;
}

// Form specifici che combinano i campi base
export interface RegisterFormData extends 
  BaseAuthFields, 
  UsernameField,
  PasswordConfirmationField {
    role: UserRoles;
}

export interface LoginFormData extends 
  BaseAuthFields,
  UsernameField {}

export interface ResetPwdFormData extends BaseAuthFields, PasswordConfirmationField {
  token: string;
}

export interface ForgotPwdFormData extends Pick<BaseAuthFields, 'email'> {}
