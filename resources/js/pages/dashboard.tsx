import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

interface Owner {
    login: string;
}

interface GitHubData {
    user: {
        login: string;
        name: string;
        avatar_url: string;
        bio: string;
        public_repos: number;
    };
    total_repos: number;
    followers: number;
    following: number;
    recent_repos: Array<{
        id: number;
        name: string;
        description: string;
        html_url: string;
        stargazers_count: number;
        language: string;
        owner: Owner;
    }>;
}

interface DashboardProps {
    githubData?: GitHubData | null;
    hasGithubToken: boolean;
}

const route = (...args: any[]) =>
    typeof window !== 'undefined' && (window as any).route
        ? (window as any).route(...args)
        : '';

export default function Dashboard({
    githubData,
    hasGithubToken,
}: DashboardProps) {
    if (!hasGithubToken) {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Dashboard" />
                <div className="flex h-full items-center justify-center">
                    <div className="text-center">
                        <h2 className="mb-4 text-2xl font-bold">
                            No GitHub Token Found
                        </h2>
                        <p className="mb-4 text-gray-600">
                            Please add your GitHub access token to view your
                            data.
                        </p>
                        <Link
                            href="/playground"
                            className="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                        >
                            Add GitHub Token
                        </Link>
                    </div>
                </div>
            </AppLayout>
        );
    }

    if (!githubData) {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Dashboard" />
                <div className="flex h-full items-center justify-center">
                    <p>Loading GitHub data...</p>
                </div>
            </AppLayout>
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                        <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Total Repositories
                        </h3>
                        <p className="mt-2 text-3xl font-bold">
                            {githubData.total_repos}
                        </p>
                    </div>
                    <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                        <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Followers
                        </h3>
                        <p className="mt-2 text-3xl font-bold">
                            {githubData.followers}
                        </p>
                    </div>
                    <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                        <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Following
                        </h3>
                        <p className="mt-2 text-3xl font-bold">
                            {githubData.following}
                        </p>
                    </div>
                </div>

                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 bg-white p-6 md:min-h-min dark:border-sidebar-border dark:bg-gray-800">
                    <div className="mb-6 flex items-center gap-4">
                        <img
                            src={githubData.user.avatar_url}
                            alt={githubData.user.name}
                            className="h-20 w-20 rounded-full"
                        />
                        <div>
                            <h2 className="text-2xl font-bold">
                                {githubData.user.name}
                            </h2>
                            <p className="text-gray-600 dark:text-gray-400">
                                @{githubData.user.login}
                            </p>
                            {githubData.user.bio && (
                                <p className="mt-2 text-sm text-gray-500">
                                    {githubData.user.bio}
                                </p>
                            )}
                        </div>
                    </div>

                    <h3 className="mb-4 text-xl font-semibold">
                        Recent Repositories
                    </h3>
                    <div className="space-y-4">
                        {githubData.recent_repos.map((repo) => (
                            <div
                                key={repo.id}
                                className="rounded-lg border p-4 dark:border-gray-700"
                            >
                                <div className="flex items-start justify-between">
                                    <div>
                                        <Link
                                            href={`/project/${repo.owner.login}/${repo.name}`}
                                            className="font-semibold text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            {repo.name}
                                        </Link>
                                        {repo.description && (
                                            <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {repo.description}
                                            </p>
                                        )}
                                    </div>
                                    <span className="text-sm text-gray-500">
                                        ⭐ {repo.stargazers_count}
                                    </span>
                                </div>
                                {repo.language && (
                                    <span className="mt-2 inline-block rounded bg-gray-100 px-2 py-1 text-xs dark:bg-gray-700">
                                        {repo.language}
                                    </span>
                                )}
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
