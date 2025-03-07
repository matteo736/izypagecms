import { Input } from "@/components/ui/input";
import { Label } from "@/Components/ui/label";
import { HTMLInputTypeAttribute } from "react";

interface LabelInputProps<T> {
  label: string;
  type: HTMLInputTypeAttribute;
  name: string;
  data: T;
  setData: (key: string, value: string) => void;
  options?: string[]; // Solo se type è "select"
  errors: Partial<Record<keyof T, string>>;
  disabled?: boolean;
}

const LabelInput = <T,>({
  label,
  type,
  name,
  data,
  setData,
  options = [],
  errors,
  disabled,
}: LabelInputProps<T>) => {
  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    setData(name, e.target.value);
  };

  const inputClassName =
    "lg:w-full sm:min-w-96 min-w-64 border border-input rounded-md focus:border-primary focus:ring-primary focus:ring-1 mt-3";

  // Estrae il messaggio d'errore per la chiave corrente
  const errorMessage = errors?.[name as keyof T];

  return (
    <li className="flex flex-col py-2">
      <Label>{label}:</Label>
      {type === "select" ? (
        <select
          className={inputClassName}
          name={name}
          value={data[name as keyof T] as string}
          onChange={handleChange}
          disabled={disabled}
        >
          {options.map((option, index) => (
            <option key={index} value={option}>
              {option}
            </option>
          ))}
        </select>
      ) : (
        <Input
          className={inputClassName}
          type={type}
          name={name}
          value={data[name as keyof T] as string}
          onChange={handleChange}
          disabled={disabled}
        />
      )}
      {errorMessage && <span className="text-destructive">{errorMessage}</span>}
    </li>
  );
};

export default LabelInput;
