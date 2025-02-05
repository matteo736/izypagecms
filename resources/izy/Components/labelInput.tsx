import { Input } from "@/components/ui/input";
import { Label } from "@/Components/ui/label";
import { HTMLInputTypeAttribute } from "react";

interface LabelInputProps<T> {
    label: string;
    type: HTMLInputTypeAttribute;
    name: string;
    data: T;
    setData: (key: string, value: string) => void; // Funzione personalizzata
    options?: string[]; // Solo se type è "select"
    errors: {
        [key in keyof T]?: string; // Ogni chiave di T può essere associata a un messaggio di errore di tipo stringa
    };
}

function LabelInput<T>({
    label,
    type,
    name,
    data,
    setData,
    options = [],
    errors
}: LabelInputProps<T>) {
    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { value } = e.target;
        setData(name, value); // Aggiorniamo utilizzando `setData`
    };

    const inputClassName =
        "lg:w-full sm:min-w-96 min-w-64 border border-input rounded-md focus:border-primary focus:ring-primary focus:ring-1 mt-3";

    // Renderizzazione dinamica in base al tipo
    if (type === "select") {
        return (
            <li className="flex flex-col py-2">
                <Label>{label}:</Label>
                <select
                    className={inputClassName}
                    name={String(name)}
                    value={data[name as keyof T] as string}
                    onChange={handleChange}
                >
                    {options.map((option, index) => (
                        <option key={index} value={option}>
                            {option}
                        </option>
                    ))}
                </select>
                {errors && <span className="text-destructive">{errors[name as keyof T] as string}</span>}
            </li>
        );
    }

    return (
        <li className="flex flex-col py-2">
            <Label>{label}:</Label>
            <Input
                className={inputClassName}
                type={type}
                name={String(name)}
                value={data[name as keyof T] as string}
                onChange={handleChange}
            />
            {errors && <span className="text-destructive">{errors[name as keyof T] as string}</span>}
        </li>
    );
}

export default LabelInput;
