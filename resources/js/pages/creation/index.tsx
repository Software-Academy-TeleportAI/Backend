import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';

const route = (...args: any[]) =>
    typeof window !== 'undefined' && (window as any).route
        ? (window as any).route(...args)
        : '';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Playground',
        href: dashboard().url,
    },
];

export default function Index() {
    const { data, setData, post, processing, errors } = useForm({
        access_token: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('playground.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Playground" />
            <span>TEST</span>
        </AppLayout>
    );
}
