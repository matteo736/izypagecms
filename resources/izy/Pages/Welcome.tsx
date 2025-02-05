import MainIzpLayout from "@/Layouts/MainIzpLayout"

export default function Welcome({ title }: { title: string }) {
    return (
        <MainIzpLayout title={title}>
            <div>Pagina Principale di IzyPage</div>
        </MainIzpLayout>
    )
}

