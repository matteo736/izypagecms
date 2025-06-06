import MainIzpLayout from "@/Layouts/MainIzpLayout"

export default function Welcome({ title }: { title: string }) {
    return (
        <MainIzpLayout title={title} >
            <p className="text-primary">Pagina Principale di IzyPage</p>
        </MainIzpLayout>
    )
}

