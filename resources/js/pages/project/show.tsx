import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

interface ProjectData {
    repository: {
        name: string;
        full_name: string;
        description: string;
        html_url: string;
        stargazers_count: number;
        watchers_count: number;
        forks_count: number;
        open_issues_count: number;
        language: string;
        created_at: string;
        updated_at: string;
        owner: {
            login: string;
            avatar_url: string;
        };
        topics: string[];
    };
    languages: Record<string, number>;
    commits: Array<{
        sha: string;
        commit: {
            message: string;
            author: {
                name: string;
                date: string;
            };
        };
        author: {
            login: string;
            avatar_url: string;
        } | null;
    }>;
    contributors: Array<{
        login: string;
        avatar_url: string;
        contributions: number;
    }>;
}

interface ProjectShowProps {
    projectData: ProjectData;
}

const route = (...args: any[]) =>
    typeof window !== 'undefined' && (window as any).route
        ? (window as any).route(...args)
        : '';

export default function ProjectShow({ projectData }: ProjectShowProps) {
    const { repository, languages, commits, contributors } = projectData;

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: dashboard().url,
        },
        {
            title: repository.name,
            href: route('project.show', {
                owner: repository.owner.login,
                repo: repository.name,
            }),
        },
    ];

    const totalLanguageBytes = Object.values(languages).reduce(
        (sum, bytes) => sum + bytes,
        0,
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={repository.name} />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
                <div className="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                    <div className="flex items-start justify-between">
                        <div className="flex items-center gap-4">
                            <img
                                src={repository.owner.avatar_url}
                                alt={repository.owner.login}
                                className="h-16 w-16 rounded-full"
                            />
                            <div>
                                <h1 className="text-3xl font-bold">
                                    {repository.name}
                                </h1>
                                <p className="text-gray-600 dark:text-gray-400">
                                    {repository.full_name}
                                </p>
                            </div>
                        </div>
                        <a
                            href={repository.html_url}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                        >
                            View on GitHub
                        </a>
                    </div>
                    {repository.description && (
                        <p className="mt-4 text-gray-700 dark:text-gray-300">
                            {repository.description}
                        </p>
                    )}
                    {repository.topics && repository.topics.length > 0 && (
                        <div className="mt-4 flex flex-wrap gap-2">
                            {repository.topics.map((topic) => (
                                <span
                                    key={topic}
                                    className="rounded-full bg-blue-100 px-3 py-1 text-xs dark:bg-blue-900"
                                >
                                    {topic}
                                </span>
                            ))}
                        </div>
                    )}
                </div>

                <div className="grid gap-4 md:grid-cols-4">
                    <div className="rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-gray-800">
                        <p className="text-sm text-gray-500">Stars</p>
                        <p className="text-2xl font-bold">
                            {repository.stargazers_count}
                        </p>
                    </div>
                    <div className="rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-gray-800">
                        <p className="text-sm text-gray-500">Forks</p>
                        <p className="text-2xl font-bold">
                            {repository.forks_count}
                        </p>
                    </div>
                    <div className="rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-gray-800">
                        <p className="text-sm text-gray-500">Watchers</p>
                        <p className="text-2xl font-bold">
                            {repository.watchers_count}
                        </p>
                    </div>
                    <div className="rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-gray-800">
                        <p className="text-sm text-gray-500">Open Issues</p>
                        <p className="text-2xl font-bold">
                            {repository.open_issues_count}
                        </p>
                    </div>
                </div>

                <div className="grid gap-6 md:grid-cols-2">
                    {/* Languages */}
                    <div className="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                        <h2 className="mb-4 text-xl font-semibold">
                            Languages
                        </h2>
                        <div className="space-y-3">
                            {Object.entries(languages).map(([lang, bytes]) => {
                                const percentage = (
                                    (bytes / totalLanguageBytes) *
                                    100
                                ).toFixed(1);
                                return (
                                    <div key={lang}>
                                        <div className="mb-1 flex justify-between text-sm">
                                            <span>{lang}</span>
                                            <span className="text-gray-500">
                                                {percentage}%
                                            </span>
                                        </div>
                                        <div className="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                                            <div
                                                className="h-2 rounded-full bg-blue-500"
                                                style={{
                                                    width: `${percentage}%`,
                                                }}
                                            />
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>

                    <div className="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                        <h2 className="mb-4 text-xl font-semibold">
                            Top Contributors
                        </h2>
                        <div className="space-y-3">
                            {contributors.map((contributor) => (
                                <div
                                    key={contributor.login}
                                    className="flex items-center justify-between"
                                >
                                    <div className="flex items-center gap-3">
                                        <img
                                            src={contributor.avatar_url}
                                            alt={contributor.login}
                                            className="h-10 w-10 rounded-full"
                                        />
                                        <span className="font-medium">
                                            {contributor.login}
                                        </span>
                                    </div>
                                    <span className="text-sm text-gray-500">
                                        {contributor.contributions} commits
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
                <div className="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                    <h2 className="mb-4 text-xl font-semibold">
                        Recent Commits
                    </h2>
                    <div className="space-y-4">
                        {commits.map((commit) => (
                            <div
                                key={commit.sha}
                                className="border-b pb-4 last:border-b-0 dark:border-gray-700"
                            >
                                <div className="flex items-start gap-3">
                                    {commit.author && (
                                        <img
                                            src={commit.author.avatar_url}
                                            alt={commit.author.login}
                                            className="h-8 w-8 rounded-full"
                                        />
                                    )}
                                    <div className="flex-1">
                                        <p className="font-medium">
                                            {
                                                commit.commit.message.split(
                                                    '\n',
                                                )[0]
                                            }
                                        </p>
                                        <p className="mt-1 text-sm text-gray-500">
                                            {commit.commit.author.name} •{' '}
                                            {new Date(
                                                commit.commit.author.date,
                                            ).toLocaleDateString()}
                                        </p>
                                    </div>
                                    <code className="rounded bg-gray-100 px-2 py-1 text-xs dark:bg-gray-700">
                                        {commit.sha.substring(0, 7)}
                                    </code>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
