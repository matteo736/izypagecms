import LabelInput from "@/Components/labelInput"
import { Button } from "@/components/ui/button"
import SetupLayout from "@/Layouts/SetupLayout"
import { useForm } from "@inertiajs/react"
import { ResetPwdFormData } from "@types/FormTypes";
import { FormEventHandler } from "react";


function ResetPassword({ status, token, email }: { status: string, token: string, email: string }) {
    const { data, setData, post, processing, errors, reset } = useForm<ResetPwdFormData>({
        token,
        email: email,
        password: '',
        password_confirmation: ''
    });
    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.store'))
    };
    return (
        <SetupLayout errors={''} message={status} title="Reset Password" className="p-4">
            <form onSubmit={submit} className="w-full max-w-md mx-auto">
                <div className="mb-4">
                    <LabelInput name="email" label="Email" type="email" value={data.email} setData={setData} errors={errors} disabled={true} />
                    <LabelInput name="password" label="Password" type="password" value={data.password} setData={setData} errors={errors} />
                    <LabelInput name="password_confirmation" label="Password Confirmation" type="password" value={data.password_confirmation} setData={setData} errors={errors} />
                </div>
                <div className="flex items-center justify-between">
                    <Button type="submit" disabled={processing}
                        className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset Password
                    </Button>
                </div>
            </form>
        </SetupLayout>
    )
}

export default ResetPassword
