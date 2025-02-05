import LabelInput from "../../Components/labelInput"
import SetupLayout from "@/Layouts/SetupLayout"
import { Field, Form } from '@types/setup/FormTypes'
import { useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";
import { Button } from "@/components/ui/button";
import { Link } from "@inertiajs/react";

function Login({ title, status, canResetPassword }: { title: string, status?: string, canResetPassword: boolean }) {

  const { data, setData, post, processing, errors, reset } = useForm({
    username: '',
    email: '',
    password: '',
  });

  const submit: FormEventHandler = (e) => {
    e.preventDefault();

    post(route('login'), {
      onFinish: () => reset('password'),
    });
  };

  const fields: Field[] = [
    { label: "Username", name: "username", type: 'text' },
    { label: "Email", name: "email", type: 'text' },
    { label: "Password", name: "password", type: 'password' }
  ];

  return (
    <SetupLayout errors={''} message={status} title={title} className="p-4">
      <form onSubmit={submit}>
        <ul className="my-2">
          {fields.map((field): React.ReactNode => {
            return <LabelInput<Form> name={field.name} label={field.label} type={field.type} setData={setData} data={data} errors={errors} />
          })}
        </ul>
        {canResetPassword && (
          <Link
            href={route('password.request')}
            className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Forgot your password?
          </Link>
        )}
        <Button type="submit" disabled={processing} variant="default" className="my-2 w-full lg:max-w-96">
          Login
        </Button>
      </form>
    </SetupLayout>
  )
}

export default Login