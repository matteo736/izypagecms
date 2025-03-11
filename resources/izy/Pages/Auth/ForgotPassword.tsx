import { FormEventHandler } from "react";
import SetupLayout from "@/Layouts/SetupLayout";
import LabelInput from "@/Components/labelInput";
import { Button } from "@/components/ui/button";
import { useForm } from "@inertiajs/react";
import { ForgotPwdFormData } from "@types/FormTypes";

function ForgotPassword({status}: {status: string}) {
    const { data, setData, post, processing, errors, reset } = useForm<ForgotPwdFormData>({
        email:'',
    });
    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.email'))
    };
    return (
        <SetupLayout errors={''} message={status} title="Reset Password" className="p-4">
            <form onSubmit={submit} className="w-full max-w-md mx-auto">
                <span>Inserisci la tua mail per ricevere il link per resettare la tua password</span>
                <div className="mb-4">
                    <LabelInput name="email" label="Email" type="email" value={data.email} setData={setData} errors={errors} />
                </div>
                <div className="flex items-center justify-between">
                    <Button type="submit" disabled={processing}
                        className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Invia
                    </Button>
                </div>
            </form>
        </SetupLayout>
    )
}

export default ForgotPassword
