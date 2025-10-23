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
        accessToken: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('playground.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Playground" />
            <div className="flex h-full items-center justify-center">
                <div className="flex flex-col items-center gap-8">
                    <img
                        width={400}
                        height={400}
                        src={'github.png'}
                        alt="GitHub logo"
                    />
                    <form
                        onSubmit={handleSubmit}
                        className="flex flex-col items-center gap-4"
                    >
                        <label htmlFor="accessToken">
                            Add your GitHub access token
                        </label>
                        <input
                            onChange={(e) =>
                                setData('accessToken', e.target.value)
                            }
                            value={data.accessToken}
                            type="text"
                            name="accessToken"
                            placeholder="Access Token"
                            className="w-[300px] rounded border p-2"
                        />
                        <button
                            type="submit"
                            className="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                        >
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
