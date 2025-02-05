import { useForm } from "@inertiajs/react";
import { Field, Form } from "@types/setup/FormTypes";
import { FormEventHandler } from "react";
import SetupLayout from "@/Layouts/SetupLayout";
import { Button } from "@/components/ui/button";
import LabelInput from "@/Components/labelInput";

function Register({ status, title }:
    { status: string, title: string }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    const fields: Field<string>[] = [
        { label: "Username", name: "username", type: 'text' },
        { label: "Email", name: "email", type: 'text' },
        { label: "Password", name: "password", type: 'password' },
        { label: "Password Confirmation", name: "password_confirmation", type: 'password' }
    ];

    return (
        <SetupLayout errors={''} message={status} title={title} className="p-4">
            <form onSubmit={submit}>
                <ul className="my-2">
                    {fields.map((field): React.ReactNode => {
                        return <LabelInput<Form> name={field.name} label={field.label} type={field.type} setData={setData} data={data} errors={errors} />
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
