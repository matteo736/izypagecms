import { useForm } from "@inertiajs/react";
import { Form, Field } from "@types/FormTypes";
import { FormEventHandler } from "react";
import SetupLayout from "@/Layouts/SetupLayout";
import { Button } from "@/components/ui/button";
import LabelInput from "@/Components/labelInput";
import UserRoles from '@types/auth/UserRoles';
import { RegisterFormData } from "@types/FormTypes";


function Register({ status, title, isFirstUser }:
    { status: string, title: string, isFirstUser: boolean }) {
    const { data, setData, post, processing, errors, reset } = useForm<RegisterFormData>({
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: isFirstUser ? UserRoles.Admin : UserRoles.Subscriber
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    const fields: Field[] = [
        { label: "Username", name: "username", type: 'text' },
        { label: "Email", name: "email", type: 'text' },
        { label: "Password", name: "password", type: 'password' },
        { label: "Password Confirmation", name: "password_confirmation", type: 'password' },
        {
            label: "Role",
            name: "role",
            type: 'select',
            options: Object.values(UserRoles),
            disabled: isFirstUser // Il campo sarà disabilitato se isFirstUser è true
        }
    ];

    return (
        <SetupLayout errors={''} message={status} title={title} className="p-4">
            <form onSubmit={submit}>
                <ul className="my-2">
                    {fields.map((field): React.ReactNode => {
                        return <LabelInput name={field.name} label={field.label} 
                                type={field.type} setData={setData} value={data[field.name as keyof RegisterFormData]} 
                                errors={errors} disabled={field.disabled} options={field.options} />
                    })}
                </ul>
                <Button type="submit" disabled={processing} variant="default" className="my-2 w-full lg:max-w-96">
                    Register
                </Button>
            </form>
        </SetupLayout>
    )
}

export default Register
