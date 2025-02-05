import LabelInput from '@/Components/labelInput';
import SetupLayout from "@/Layouts/SetupLayout";
import { Button } from '@/components/ui/button';
import { Head, useForm } from '@inertiajs/react';
import { Password } from '@types/setup/FormTypes';
import { Field } from '@types/setup/FormTypes';
import { FormEventHandler } from 'react';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('password.confirm'), {
            onFinish: () => reset('password'),
        });
    };

    const passwordField: Field<keyof Password> = 
        { label: "Confirm Password", name: "password", type: 'password' };

    return (
        <SetupLayout title={'Confirm Password'} message={''} errors={''} className='p-0'>
            <div className="my-2 mb-4 text-sm text-gray-600">
                This is a secure area of the application. Please confirm your
                password before continuing.
            </div>

            <form onSubmit={submit}>
                <LabelInput<Password> label={passwordField.label} name={passwordField.name} 
                    type={passwordField.type} data={data} setData={setData} errors={errors} />
                <div className="mt-4 flex items-center justify-end">
                    <Button type="submit" disabled={processing} variant="default" className="my-2 w-full md:max-w-32">
                        Confirm
                    </Button>
                </div>
            </form>
        </SetupLayout >
    );
}
