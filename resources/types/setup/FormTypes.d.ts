import { Type } from "lucide-react";
import { HTMLInputTypeAttribute } from "react";

//export type FieldType = 'text' | 'number' | 'password' | 'select' | 'textarea' | boolean;

// Definizione generica di un campo
export type Field = {
  label: string;
  name: string;
  type: HTMLInputTypeAttribute;
  options?: string[]; // Per i campi 'select'
}

// Definisci un tipo per un form generico
export interface Form {
  [key: string]: HTMLInputTypeAttribute | boolean ; // Usa la chiave dinamica per ogni campo
}


