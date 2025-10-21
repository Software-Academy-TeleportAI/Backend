import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Playground',
        href: dashboard().url,
    },
];

export default function Playground() {
    const theme = localStorage.getItem('appearance');

    const form = useForm({
        accessToken: '',
    });

    const isDarkMode = theme === 'dark';

    const submitButton = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Playground" />
            <div className="flex h-full items-center justify-center">
                <div className="flex flex-col items-center gap-8">
                    <img
                        width={isDarkMode ? 300 : 400}
                        height={400}
                        src={
                            isDarkMode ? 'github-dark-mode.webp' : 'github.png'
                        }
                        alt="GitHub logo"
                    />
                    <form
                        onSubmit={submitButton}
                        className="flex flex-col items-center gap-4"
                    >
                        <label htmlFor="accessToken">
                            Add your GitHub access token
                        </label>
                        <input
                            onChange={(e) =>
                                form.setData('accessToken', e.target.value)
                            }
                            value={form.data.accessToken}
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
